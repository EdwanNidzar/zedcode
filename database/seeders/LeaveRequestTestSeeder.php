<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use Illuminate\Support\Facades\Hash;
use App\Services\ApprovalChainService;

class LeaveRequestTestSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan DatabaseSeeder sudah dijalankan agar role & user utama tersedia
        $pemohon = User::where('email', 'edwan@zedcore.test')->first();
        
        if (!$pemohon) {
            $this->command->warn('User Edwan tidak ditemukan. Pastikan Anda menjalankan DatabaseSeeder terlebih dahulu.');
            return;
        }

        // Buat satu karyawan setara untuk dijadikan Staff Pengganti
        $pengganti = User::firstOrCreate(
            ['email' => 'budi@zedcore.test'],
            [
                'name' => 'Budi (Rekan Edwan)',
                'password' => Hash::make('password'),
                'jabatan' => 'Staff IT',
                'divisi' => 'IT',
                'atasan_id' => $pemohon->atasan_id, // Atasan yang sama (Naldi/SPV)
            ]
        );
        $pengganti->syncRoles(['Employee']);

        // Set hak cuti untuk Edwan
        LeaveBalance::firstOrCreate(
            ['user_id' => $pemohon->id, 'leave_type' => 'Cuti Tahunan', 'year' => date('Y')],
            ['balance' => 12]
        );

        // Buat Pengajuan Cuti Dummy
        $leaveRequest = LeaveRequest::create([
            'user_id' => $pemohon->id,
            'leave_type' => 'Cuti',
            'reason' => 'Urusan keluarga di kampung',
            'start_date' => now()->addDays(3)->format('Y-m-d'),
            'end_date' => now()->addDays(5)->format('Y-m-d'),
            'jumlah_hari' => 3,
            'tanggal_kembali' => now()->addDays(6)->format('Y-m-d'),
            'no_telp_darurat' => '0895605330279',
            'pengganti_user_id' => $pengganti->id,
            'status_pengganti' => 'pending',
        ]);

        // Generate Rantai Approval menggunakan service yang sebenarnya
        app(ApprovalChainService::class)->createApprovalChain($leaveRequest, $pengganti);

        $this->command->info('✅ Leave Request Dummy berhasil dibuat!');
        $this->command->info('Pemohon: Edwan');
        $this->command->info('Pengganti: Budi');
        $this->command->info('Silakan login sebagai budi@zedcore.test (pass: password) untuk menyetujui sebagai pengganti.');
    }
}
