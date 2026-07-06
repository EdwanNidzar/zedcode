<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Models\LeaveApproval;
use App\Services\ApprovalChainService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class KasirLeaveApprovalTest extends TestCase
{
    use RefreshDatabase;

    private ApprovalChainService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup semua Role dengan level
        Role::firstOrCreate(['name' => 'Direktur'])->update(['level' => 6]);
        Role::firstOrCreate(['name' => 'Manager / GM'])->update(['level' => 5]);
        Role::firstOrCreate(['name' => 'HR / Manager'])->update(['level' => 4]);
        Role::firstOrCreate(['name' => 'SPV'])->update(['level' => 3]);
        Role::firstOrCreate(['name' => 'Employee'])->update(['level' => 2]);

        $this->service = app(ApprovalChainService::class);

        // Suppress notif supaya tidak error saat user punya no mail driver di test
        Notification::fake();
    }

    /**
     * Helper: Buat hirarki user kasir lengkap
     */
    private function buildKasirHierarchy(): array
    {
        $direktur = tap(User::factory()->create(['name' => 'Pak Direktur']))
            ->syncRoles(['Direktur']);

        $hrd = tap(User::factory()->create(['name' => 'Bu HRD', 'atasan_id' => $direktur->id]))
            ->syncRoles(['HR / Manager']);

        $manager = tap(User::factory()->create(['name' => 'Pak Manager', 'atasan_id' => $direktur->id]))
            ->syncRoles(['Manager / GM']);

        $spv = tap(User::factory()->create(['name' => 'Bu SPV', 'atasan_id' => $manager->id]))
            ->syncRoles(['SPV']);

        $kasir = tap(User::factory()->create(['name' => 'Kasir Pemohon', 'atasan_id' => $spv->id]))
            ->syncRoles(['Employee']);

        $pengganti = tap(User::factory()->create(['name' => 'Kasir Pengganti', 'atasan_id' => $spv->id]))
            ->syncRoles(['Employee']);

        return compact('direktur', 'hrd', 'manager', 'spv', 'kasir', 'pengganti');
    }

    /**
     * Helper: Buat LeaveRequest kasir
     */
    private function buildLeaveRequest(User $kasir, User $pengganti): LeaveRequest
    {
        LeaveBalance::create([
            'user_id'    => $kasir->id,
            'leave_type' => 'Cuti Tahunan',
            'year'       => date('Y'),
            'balance'    => 12,
        ]);

        $leaveRequest = LeaveRequest::create([
            'user_id'          => $kasir->id,
            'leave_type'       => 'Cuti',
            'reason'           => 'Liburan akhir tahun',
            'start_date'       => now()->addDays(2)->format('Y-m-d'),
            'end_date'         => now()->addDays(4)->format('Y-m-d'),
            'jumlah_hari'      => 3,
            'tanggal_kembali'  => now()->addDays(5)->format('Y-m-d'),
            'no_telp_darurat'  => '08123456789',
            'pengganti_user_id' => $pengganti->id,
            'status_pengganti' => 'pending',
        ]);

        $this->service->createApprovalChain($leaveRequest, $pengganti);

        return $leaveRequest;
    }

    /** ------------------------------------------------------------------ *
     *  Test 1: Rantai approval terbentuk dengan benar
     * ------------------------------------------------------------------ */
    public function test_approval_chain_is_created_with_correct_roles()
    {
        ['kasir' => $kasir, 'pengganti' => $pengganti] = $this->buildKasirHierarchy();
        $leaveRequest = $this->buildLeaveRequest($kasir, $pengganti);

        $approvals = $leaveRequest->approvals()->orderBy('order')->get();

        // Kasir (Employee, level 2) → SPV + HR + Manager + Direktur = 4 approver
        $this->assertCount(4, $approvals);
        $this->assertEquals('SPV',          $approvals[0]->role_label);
        $this->assertEquals('HR / Manager', $approvals[1]->role_label);
        $this->assertEquals('Manager / GM', $approvals[2]->role_label);
        $this->assertEquals('Direktur',     $approvals[3]->role_label);

        // Semua awalnya pending
        foreach ($approvals as $a) {
            $this->assertEquals('pending', $a->status);
        }

        // Status keseluruhan pending karena pengganti belum approve
        $this->assertEquals('pending', $leaveRequest->overall_status);
    }

    /** ------------------------------------------------------------------ *
     *  Test 2: Flow approve pengganti memperbarui status dengan benar
     * ------------------------------------------------------------------ */
    public function test_pengganti_approval_changes_status_to_in_progress()
    {
        ['kasir' => $kasir, 'pengganti' => $pengganti] = $this->buildKasirHierarchy();
        $leaveRequest = $this->buildLeaveRequest($kasir, $pengganti);

        // Simulate: pengganti approve
        $leaveRequest->update(['status_pengganti' => 'approved']);
        $leaveRequest->refresh();

        $this->assertEquals('approved', $leaveRequest->status_pengganti);
        $this->assertEquals('in_progress', $leaveRequest->overall_status);
    }

    /** ------------------------------------------------------------------ *
     *  Test 3: Full happy-path — semua approve satu per satu
     * ------------------------------------------------------------------ */
    public function test_full_approval_chain_results_in_approved_status()
    {
        ['kasir' => $kasir, 'pengganti' => $pengganti] = $this->buildKasirHierarchy();
        $leaveRequest = $this->buildLeaveRequest($kasir, $pengganti);

        // Pengganti approve
        $leaveRequest->update(['status_pengganti' => 'approved']);

        $approvals = $leaveRequest->approvals()->orderBy('order')->get();

        // SPV → HR → Manager → Direktur
        foreach ($approvals as $approval) {
            $this->assertEquals('in_progress', $leaveRequest->fresh()->overall_status);

            $this->service->processApproval($approval->fresh(), true);
        }

        $leaveRequest->refresh();
        $this->assertEquals('approved', $leaveRequest->overall_status);
    }

    /** ------------------------------------------------------------------ *
     *  Test 4: Reject di tengah chain menghentikan semua step berikutnya
     * ------------------------------------------------------------------ */
    public function test_rejection_cancels_remaining_approvals()
    {
        ['kasir' => $kasir, 'pengganti' => $pengganti, 'spv' => $spv] = $this->buildKasirHierarchy();
        $leaveRequest = $this->buildLeaveRequest($kasir, $pengganti);

        // Pengganti & SPV sudah approve
        $leaveRequest->update(['status_pengganti' => 'approved']);

        $approvals = $leaveRequest->approvals()->orderBy('order')->get();
        $this->service->processApproval($approvals[0]->fresh(), true); // SPV approve

        // HR reject
        $this->service->processApproval($approvals[1]->fresh(), false, 'Alasan penolakan HR');

        $leaveRequest->refresh();

        // Status keseluruhan harus rejected
        $this->assertEquals('rejected', $leaveRequest->overall_status);

        // Step setelah HR (Manager, Direktur) harus ikut ditolak
        $remainingStatuses = $leaveRequest->approvals()
            ->whereIn('order', [3, 4])
            ->pluck('status')
            ->unique()
            ->values()
            ->toArray();

        $this->assertEquals(['rejected'], $remainingStatuses);
    }

    /** ------------------------------------------------------------------ *
     *  Test 5: Direktur adalah approver terakhir (tidak diteruskan ke CEO)
     * ------------------------------------------------------------------ */
    public function test_approval_chain_stops_at_direktur_for_employee()
    {
        ['kasir' => $kasir, 'pengganti' => $pengganti] = $this->buildKasirHierarchy();
        $leaveRequest = $this->buildLeaveRequest($kasir, $pengganti);

        $roleLabels = $leaveRequest->approvals()->pluck('role_label')->toArray();

        $this->assertNotContains('CEO', $roleLabels);
        $this->assertContains('Direktur', $roleLabels);
    }
}
