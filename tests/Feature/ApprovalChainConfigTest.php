<?php

namespace Tests\Feature;

use App\Models\ApprovalChainConfig;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Models\User;
use App\Services\ApprovalChainService;
use Database\Seeders\ApprovalChainConfigSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApprovalChainConfigTest extends TestCase
{
    use RefreshDatabase;

    private ApprovalChainService $service;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'CEO'])->update(['level' => 7]);
        Role::firstOrCreate(['name' => 'Direktur'])->update(['level' => 6]);
        Role::firstOrCreate(['name' => 'Manager / GM'])->update(['level' => 5]);
        Role::firstOrCreate(['name' => 'HR / Manager'])->update(['level' => 4]);
        Role::firstOrCreate(['name' => 'SPV'])->update(['level' => 3]);
        Role::firstOrCreate(['name' => 'Employee'])->update(['level' => 2]);

        $this->service = app(ApprovalChainService::class);
        Notification::fake();
    }

    /** Helper: bangun hirarki user standar */
    private function buildHierarchy(): array
    {
        $direktur = tap(User::factory()->create(['name' => 'Pak Direktur']))->syncRoles(['Direktur']);
        $manager  = tap(User::factory()->create(['name' => 'Pak Manager', 'atasan_id' => $direktur->id]))->syncRoles(['Manager / GM']);
        $hrd      = tap(User::factory()->create(['name' => 'Bu HRD', 'atasan_id' => $direktur->id]))->syncRoles(['HR / Manager']);
        $spv      = tap(User::factory()->create(['name' => 'Bu SPV', 'atasan_id' => $manager->id]))->syncRoles(['SPV']);
        $employee = tap(User::factory()->create(['name' => 'Karyawan', 'atasan_id' => $spv->id]))->syncRoles(['Employee']);
        $pengganti = tap(User::factory()->create(['name' => 'Pengganti', 'atasan_id' => $spv->id]))->syncRoles(['Employee']);
        return compact('direktur', 'manager', 'hrd', 'spv', 'employee', 'pengganti');
    }

    // ------------------------------------------------------------------ //
    // Test 1: Fallback ke default jika DB kosong
    // ------------------------------------------------------------------ //
    public function test_falls_back_to_hardcoded_default_when_db_is_empty()
    {
        $this->assertDatabaseCount('approval_chain_configs', 0);

        ['employee' => $employee] = $this->buildHierarchy();
        $steps = $this->service->getChainSteps($this->service->getUserLevel($employee));

        // Default untuk Employee = SPV, HR, Manager, Direktur
        $this->assertCount(4, $steps);
        $roles = array_column($steps, 0);
        $this->assertContains('SPV', $roles);
        $this->assertContains('HR / Manager', $roles);
        $this->assertContains('Manager / GM', $roles);
        $this->assertContains('Direktur', $roles);
    }

    // ------------------------------------------------------------------ //
    // Test 2: Seeder mengisi config default dengan benar
    // ------------------------------------------------------------------ //
    public function test_seeder_populates_default_configs()
    {
        $this->seed(ApprovalChainConfigSeeder::class);

        // Employee (level 2) harus punya 4 step
        $this->assertDatabaseCount('approval_chain_configs', 16); // 4+4+3+2+2+1 = 16
        
        $employeeConfigs = ApprovalChainConfig::where('requester_level', 2)
            ->orderBy('step_order')
            ->pluck('approver_role')
            ->toArray();

        $this->assertEquals(['SPV', 'HR / Manager', 'Manager / GM', 'Direktur'], $employeeConfigs);
    }

    // ------------------------------------------------------------------ //
    // Test 3: Service membaca dari DB jika config tersedia
    // ------------------------------------------------------------------ //
    public function test_service_reads_chain_from_db_when_configured()
    {
        ['employee' => $employee, 'spv' => $spv] = $this->buildHierarchy();
        $pemohonLevel = $this->service->getUserLevel($employee);

        // Set config di DB: hanya SPV dan Direktur (skip HR dan Manager)
        ApprovalChainConfig::create(['requester_level' => $pemohonLevel, 'approver_role' => 'SPV', 'approver_level' => 3, 'step_order' => 1, 'is_active' => true]);
        ApprovalChainConfig::create(['requester_level' => $pemohonLevel, 'approver_role' => 'Direktur', 'approver_level' => 6, 'step_order' => 2, 'is_active' => true]);

        $steps = $this->service->getChainSteps($pemohonLevel);

        $this->assertCount(2, $steps);
        $this->assertEquals('SPV', $steps[0][0]);
        $this->assertEquals('Direktur', $steps[1][0]);
    }

    // ------------------------------------------------------------------ //
    // Test 4: Step nonaktif tidak masuk ke chain
    // ------------------------------------------------------------------ //
    public function test_inactive_steps_are_excluded_from_chain()
    {
        ['employee' => $employee] = $this->buildHierarchy();
        $pemohonLevel = $this->service->getUserLevel($employee);

        ApprovalChainConfig::create(['requester_level' => $pemohonLevel, 'approver_role' => 'SPV',         'approver_level' => 3, 'step_order' => 1, 'is_active' => true]);
        ApprovalChainConfig::create(['requester_level' => $pemohonLevel, 'approver_role' => 'HR / Manager', 'approver_level' => 4, 'step_order' => 2, 'is_active' => false]); // nonaktif
        ApprovalChainConfig::create(['requester_level' => $pemohonLevel, 'approver_role' => 'Direktur',     'approver_level' => 6, 'step_order' => 3, 'is_active' => true]);

        $steps = $this->service->getChainSteps($pemohonLevel);

        $roles = array_column($steps, 0);
        $this->assertCount(2, $steps);
        $this->assertNotContains('HR / Manager', $roles);
        $this->assertContains('SPV', $roles);
        $this->assertContains('Direktur', $roles);
    }

    // ------------------------------------------------------------------ //
    // Test 5: HR mengubah chain → pengajuan baru pakai chain baru
    // ------------------------------------------------------------------ //
    public function test_new_leave_request_uses_updated_chain()
    {
        ['employee' => $employee, 'pengganti' => $pengganti, 'spv' => $spv, 'direktur' => $direktur] = $this->buildHierarchy();
        $pemohonLevel = $this->service->getUserLevel($employee);

        // HR mengonfigurasi: Employee hanya perlu SPV + Direktur (skip HR dan Manager)
        ApprovalChainConfig::create(['requester_level' => $pemohonLevel, 'approver_role' => 'SPV',      'approver_level' => 3, 'step_order' => 1, 'is_active' => true]);
        ApprovalChainConfig::create(['requester_level' => $pemohonLevel, 'approver_role' => 'Direktur', 'approver_level' => 6, 'step_order' => 2, 'is_active' => true]);

        LeaveBalance::create(['user_id' => $employee->id, 'leave_type' => 'Cuti Tahunan', 'year' => date('Y'), 'balance' => 12]);

        $leaveRequest = LeaveRequest::create([
            'user_id'          => $employee->id,
            'leave_type'       => 'Cuti',
            'reason'           => 'Test',
            'start_date'       => now()->addDays(2)->format('Y-m-d'),
            'end_date'         => now()->addDays(3)->format('Y-m-d'),
            'jumlah_hari'      => 2,
            'tanggal_kembali'  => now()->addDays(4)->format('Y-m-d'),
            'no_telp_darurat'  => '08123',
            'pengganti_user_id' => $pengganti->id,
            'status_pengganti' => 'pending',
        ]);

        $this->service->createApprovalChain($leaveRequest, $pengganti);

        $roleLabels = $leaveRequest->approvals()->pluck('role_label')->toArray();

        $this->assertCount(2, $roleLabels);
        $this->assertContains('SPV', $roleLabels);
        $this->assertContains('Direktur', $roleLabels);
        $this->assertNotContains('HR / Manager', $roleLabels);
        $this->assertNotContains('Manager / GM', $roleLabels);
    }

    // ------------------------------------------------------------------ //
    // Test 6: Chain lama (pengajuan in-flight) tidak terpengaruh perubahan config
    // ------------------------------------------------------------------ //
    public function test_existing_in_progress_leave_not_affected_by_config_change()
    {
        ['employee' => $employee, 'pengganti' => $pengganti, 'spv' => $spv] = $this->buildHierarchy();
        $pemohonLevel = $this->service->getUserLevel($employee);

        // Config awal: 4 step
        $this->seed(ApprovalChainConfigSeeder::class);

        LeaveBalance::create(['user_id' => $employee->id, 'leave_type' => 'Cuti Tahunan', 'year' => date('Y'), 'balance' => 12]);

        $leaveRequest = LeaveRequest::create([
            'user_id'          => $employee->id,
            'leave_type'       => 'Cuti',
            'reason'           => 'Test',
            'start_date'       => now()->addDays(2)->format('Y-m-d'),
            'end_date'         => now()->addDays(3)->format('Y-m-d'),
            'jumlah_hari'      => 2,
            'tanggal_kembali'  => now()->addDays(4)->format('Y-m-d'),
            'no_telp_darurat'  => '08123',
            'pengganti_user_id' => $pengganti->id,
            'status_pengganti' => 'pending',
        ]);

        $this->service->createApprovalChain($leaveRequest, $pengganti);
        $this->assertCount(4, $leaveRequest->approvals); // 4 step tersimpan di DB

        // HR mengubah config: sekarang hanya 2 step
        ApprovalChainConfig::where('requester_level', $pemohonLevel)->delete();
        ApprovalChainConfig::create(['requester_level' => $pemohonLevel, 'approver_role' => 'SPV',      'approver_level' => 3, 'step_order' => 1, 'is_active' => true]);
        ApprovalChainConfig::create(['requester_level' => $pemohonLevel, 'approver_role' => 'Direktur', 'approver_level' => 6, 'step_order' => 2, 'is_active' => true]);

        // Pengajuan lama tetap punya 4 step (tidak berubah)
        $this->assertCount(4, $leaveRequest->fresh()->approvals);
    }
}
