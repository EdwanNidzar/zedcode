<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApprovalChainConfig;

class ApprovalChainConfigSeeder extends Seeder
{
    /**
     * Isi konfigurasi default yang mencerminkan aturan hardcoded sebelumnya.
     * HR bisa mengubah ini via UI setelah seeder berjalan.
     *
     * Format tiap baris: [requester_level, approver_role, approver_level, step_order]
     */
    private const DEFAULTS = [
        // Intern (level 1): sama seperti Employee
        ['requester_level' => 1, 'approver_role' => 'SPV',          'approver_level' => 3, 'step_order' => 1],
        ['requester_level' => 1, 'approver_role' => 'HR / Manager',  'approver_level' => 4, 'step_order' => 2],
        ['requester_level' => 1, 'approver_role' => 'Manager / GM',  'approver_level' => 5, 'step_order' => 3],
        ['requester_level' => 1, 'approver_role' => 'Direktur',      'approver_level' => 6, 'step_order' => 4],

        // Employee (level 2): SPV → HR → Manager → Direktur
        ['requester_level' => 2, 'approver_role' => 'SPV',          'approver_level' => 3, 'step_order' => 1],
        ['requester_level' => 2, 'approver_role' => 'HR / Manager',  'approver_level' => 4, 'step_order' => 2],
        ['requester_level' => 2, 'approver_role' => 'Manager / GM',  'approver_level' => 5, 'step_order' => 3],
        ['requester_level' => 2, 'approver_role' => 'Direktur',      'approver_level' => 6, 'step_order' => 4],

        // SPV (level 3): HR → Manager → Direktur
        ['requester_level' => 3, 'approver_role' => 'HR / Manager',  'approver_level' => 4, 'step_order' => 1],
        ['requester_level' => 3, 'approver_role' => 'Manager / GM',  'approver_level' => 5, 'step_order' => 2],
        ['requester_level' => 3, 'approver_role' => 'Direktur',      'approver_level' => 6, 'step_order' => 3],

        // HR / Manager (level 4): Manager → Direktur
        ['requester_level' => 4, 'approver_role' => 'Manager / GM',  'approver_level' => 5, 'step_order' => 1],
        ['requester_level' => 4, 'approver_role' => 'Direktur',      'approver_level' => 6, 'step_order' => 2],

        // Manager / GM (level 5): Direktur → CEO
        ['requester_level' => 5, 'approver_role' => 'Direktur',      'approver_level' => 6, 'step_order' => 1],
        ['requester_level' => 5, 'approver_role' => 'CEO',           'approver_level' => 7, 'step_order' => 2],

        // Direktur (level 6): CEO
        ['requester_level' => 6, 'approver_role' => 'CEO',           'approver_level' => 7, 'step_order' => 1],
    ];

    public function run(): void
    {
        foreach (self::DEFAULTS as $config) {
            ApprovalChainConfig::firstOrCreate(
                [
                    'requester_level' => $config['requester_level'],
                    'approver_role'   => $config['approver_role'],
                ],
                [
                    'approver_level' => $config['approver_level'],
                    'step_order'     => $config['step_order'],
                    'is_active'      => true,
                ]
            );
        }

        $this->command->info('✅ Approval Chain Config default berhasil di-seed!');
    }
}
