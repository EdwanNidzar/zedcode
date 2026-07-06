<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Services\ApprovalChainService;

class KasirLeaveTestSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan DatabaseSeeder sudah dijalankan agar role utama tersedia
        $direktur = User::where('email', 'direktur@zedcore.test')->first();
        if (!$direktur) {
            $this->command->warn('User Direktur tidak ditemukan. Jalankan DatabaseSeeder dulu.');
            return;
        }

        // 1. Buat User Manager / GM (Karena di DatabaseSeeder belum ada)
        $managerRole = Role::firstOrCreate(['name' => 'Manager / GM', 'guard_name' => 'web']);
        $managerRole->update(['level' => 5]);
        
        $manager = User::firstOrCreate(
            ['email' => 'manager_ops@zedcore.test'],
            [
                'name' => 'Pak Manager Ops',
                'password' => Hash::make('password'),
                'jabatan' => 'Manager Operasional',
                'divisi' => 'Operasional',
                'atasan_id' => $direktur->id, // Lapor ke direktur
            ]
        );
        $manager->syncRoles(['Manager / GM']);

        // 2. Buat User SPV Kasir
        $spvKasir = User::firstOrCreate(
            ['email' => 'spv_kasir@zedcore.test'],
            [
                'name' => 'Bu SPV Kasir',
                'password' => Hash::make('password'),
                'jabatan' => 'Supervisor Kasir',
                'divisi' => 'Operasional',
                'atasan_id' => $manager->id, // Lapor ke manager
            ]
        );
        $spvKasir->syncRoles(['SPV']);

        // 3. Buat User Kasir (Pemohon)
        $kasir = User::firstOrCreate(
            ['email' => 'kasir1@zedcore.test'],
            [
                'name' => 'Siti (Kasir 1)',
                'password' => Hash::make('password'),
                'jabatan' => 'Kasir',
                'divisi' => 'Operasional',
                'atasan_id' => $spvKasir->id, // Lapor ke SPV
            ]
        );
        $kasir->syncRoles(['Employee']);

        // 4. Buat User Kasir Pengganti
        $kasirPengganti = User::firstOrCreate(
            ['email' => 'kasir2@zedcore.test'],
            [
                'name' => 'Rahma (Kasir 2)',
                'password' => Hash::make('password'),
                'jabatan' => 'Kasir',
                'divisi' => 'Operasional',
                'atasan_id' => $spvKasir->id, // Atasan yang sama
            ]
        );
        $kasirPengganti->syncRoles(['Employee']);

        // Set hak cuti untuk Kasir
        LeaveBalance::firstOrCreate(
            ['user_id' => $kasir->id, 'leave_type' => 'Cuti Tahunan', 'year' => date('Y')],
            ['balance' => 12]
        );

        // 5. Buat Pengajuan Cuti Dummy
        $leaveRequest = LeaveRequest::create([
            'user_id' => $kasir->id,
            'leave_type' => 'Cuti',
            'reason' => 'Liburan akhir tahun',
            'start_date' => now()->addDays(10)->format('Y-m-d'),
            'end_date' => now()->addDays(12)->format('Y-m-d'),
            'jumlah_hari' => 3,
            'tanggal_kembali' => now()->addDays(13)->format('Y-m-d'),
            'no_telp_darurat' => '08123456789',
            'pengganti_user_id' => $kasirPengganti->id,
            'status_pengganti' => 'pending',
        ]);

        // Generate Rantai Approval
        app(ApprovalChainService::class)->createApprovalChain($leaveRequest, $kasirPengganti);

        $this->command->info('✅ Leave Request Kasir berhasil dibuat!');
        $this->command->info('Pemohon: Siti (kasir1@zedcore.test)');
        $this->command->info('Pengganti: Rahma (kasir2@zedcore.test)');
        
        $this->command->warn('Rantai Approval yang terbentuk:');
        foreach ($leaveRequest->approvals()->orderBy('order')->get() as $approval) {
            $this->command->line("- [Level {$approval->order}] {$approval->role_label} ({$approval->approver->name})");
        }
    }
}
