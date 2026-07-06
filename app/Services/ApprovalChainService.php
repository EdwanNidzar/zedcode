<?php

namespace App\Services;

use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\LeaveApproval;
use App\Models\ApprovalChainConfig;
use Spatie\Permission\Models\Role;

class ApprovalChainService
{
    /**
     * Level Jabatan berdasarkan role Spatie.
     * Semakin tinggi angka = semakin tinggi jabatan.
     */
    public const ROLE_LEVELS = [
        'Intern'        => 1,
        'Employee'      => 2,
        'SPV'           => 3,
        'HR / Manager'  => 4,
        'Manager / GM'  => 5,
        'Direktur'      => 6,
        'CEO'           => 7,
        'Super Admin'   => 8,
    ];

    /**
     * Default fallback chain jika tabel approval_chain_configs kosong.
     * Format: requester_level => [[approver_role, approver_level], ...]
     */
    private const DEFAULT_CHAIN = [
        1 => [['SPV', 3], ['HR / Manager', 4], ['Manager / GM', 5], ['Direktur', 6]],
        2 => [['SPV', 3], ['HR / Manager', 4], ['Manager / GM', 5], ['Direktur', 6]],
        3 => [['HR / Manager', 4], ['Manager / GM', 5], ['Direktur', 6]],
        4 => [['Manager / GM', 5], ['Direktur', 6]],
        5 => [['Direktur', 6], ['CEO', 7]],
        6 => [['CEO', 7]],
    ];

    /**
     * Membangun rantai approver berdasarkan konfigurasi DB (atau fallback default).
     * Mengembalikan array of ['approver' => User, 'role_label' => string].
     */
    public function buildChain(User $pemohon, User $pengganti): array
    {
        $pemohonLevel = $this->getUserLevel($pemohon);
        $steps        = $this->getChainSteps($pemohonLevel);
        $chain        = [];

        foreach ($steps as [$roleName]) {
            $approver = $this->findApproverByRole($roleName, $pemohon);
            if ($approver) {
                $chain[] = [
                    'approver'   => $approver,
                    'role_label' => $roleName,
                ];
            }
        }

        return $chain;
    }

    /**
     * Ambil daftar step rantai dari DB.
     * Fallback ke DEFAULT_CHAIN jika tidak ada config aktif.
     *
     * @return array  [[roleName, level], ...]
     */
    public function getChainSteps(int $pemohonLevel): array
    {
        $configs = ApprovalChainConfig::active()
            ->forLevel($pemohonLevel)
            ->orderBy('step_order')
            ->get();

        if ($configs->isNotEmpty()) {
            return $configs->map(fn($c) => [$c->approver_role, $c->approver_level])->toArray();
        }

        // Fallback ke hardcoded default
        return self::DEFAULT_CHAIN[$pemohonLevel] ?? [];
    }

    /**
     * Menentukan level jabatan seorang user berdasarkan role Spatie.
     */
    public function getUserLevel(User $user): int
    {
        $maxLevel = 0;
        foreach ($user->roles as $role) {
            $level = self::ROLE_LEVELS[$role->name] ?? 0;
            if ($role->name !== 'Super Admin' && $level > $maxLevel) {
                $maxLevel = $level;
            }
        }
        return $maxLevel ?: 2;
    }

    /**
     * Cari user yang menjadi approver untuk role tertentu.
     * Prioritas: 1) atasan langsung jika role-nya cocok, 2) siapa pun dengan role itu.
     */
    private function findApproverByRole(string $roleName, User $pemohon): ?User
    {
        $atasan = $pemohon->atasan;
        if ($atasan && $atasan->hasRole($roleName)) {
            return $atasan;
        }

        return User::role($roleName)->first();
    }

    /**
     * Membuat entri leave_approvals saat pengajuan dikirim.
     */
    public function createApprovalChain(LeaveRequest $leaveRequest, User $pengganti): void
    {
        $pemohon = $leaveRequest->user;
        $chain   = $this->buildChain($pemohon, $pengganti);

        foreach ($chain as $order => $item) {
            LeaveApproval::create([
                'leave_request_id' => $leaveRequest->id,
                'approver_id'      => $item['approver']->id,
                'order'            => $order + 1,
                'role_label'       => $item['role_label'],
                'status'           => 'pending',
            ]);
        }
    }

    /**
     * Memproses approval: setujui satu langkah dan kirim notifikasi ke berikutnya.
     */
    public function processApproval(LeaveApproval $approval, bool $approved, ?string $catatan = null): void
    {
        $approval->update([
            'status'      => $approved ? 'approved' : 'rejected',
            'actioned_at' => now(),
            'catatan'     => $catatan,
        ]);

        $leaveRequest = $approval->leaveRequest->load('approvals.approver', 'user');

        if (!$approved) {
            $leaveRequest->approvals()->where('status', 'pending')->update(['status' => 'rejected']);
            $leaveRequest->user->notify(new \App\Notifications\LeaveStatusNotification($leaveRequest, 'rejected', $approval->approver));
            return;
        }

        $nextApproval = $leaveRequest->approvals()
            ->where('order', '>', $approval->order)
            ->where('status', 'pending')
            ->orderBy('order')
            ->first();

        if ($nextApproval) {
            $nextApproval->approver->notify(new \App\Notifications\LeaveApprovalRequestNotification($leaveRequest, $nextApproval));
        } else {
            $leaveRequest->user->notify(new \App\Notifications\LeaveStatusNotification($leaveRequest, 'approved', null));
        }
    }
}
