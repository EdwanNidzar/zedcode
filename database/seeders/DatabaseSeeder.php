<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ======================================================
        // 1. Buat Semua Role dengan Level Jabatan
        // ======================================================
        $roles = [
            ['name' => 'Super Admin',  'level' => 8], // Teknis, bukan struktural
            ['name' => 'CEO',          'level' => 7],
            ['name' => 'Direktur',     'level' => 6],
            ['name' => 'Manager / GM', 'level' => 5],
            ['name' => 'HR / Manager', 'level' => 4],
            ['name' => 'SPV',          'level' => 3],
            ['name' => 'Employee',     'level' => 2],
            ['name' => 'Intern',       'level' => 1],
        ];

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(['name' => $roleData['name']], ['guard_name' => 'web']);
            $role->update(['level' => $roleData['level']]);
        }

        // ======================================================
        // 2. Super Admin (Akses Teknis Penuh)
        // ======================================================
        $superAdmin = User::firstOrCreate(['email' => 'admin@zedcore.test'], [
            'name'     => 'Super Admin',
            'password' => Hash::make('password'),
            'jabatan'  => 'System Administrator',
            'divisi'   => 'IT',
        ]);
        $superAdmin->syncRoles(['Super Admin']);

        // ======================================================
        // 3. CEO
        // ======================================================
        $ceo = User::firstOrCreate(['email' => 'ceo@zedcore.test'], [
            'name'     => 'CEO',
            'password' => Hash::make('password'),
            'jabatan'  => 'Chief Executive Officer',
            'divisi'   => 'Direksi',
        ]);
        $ceo->syncRoles(['CEO']);

        // ======================================================
        // 4. Direktur
        // ======================================================
        $direktur = User::firstOrCreate(['email' => 'direktur@zedcore.test'], [
            'name'     => 'Nafarin',
            'password' => Hash::make('password'),
            'jabatan'  => 'Direktur',
            'divisi'   => 'Direksi',
            'atasan_id' => $ceo->id,
        ]);
        $direktur->syncRoles(['Direktur']);

        // ======================================================
        // 5. HR / Manager
        // ======================================================
        $hrd = User::firstOrCreate(['email' => 'ida@zedcore.test'], [
            'name'     => 'Ida',
            'password' => Hash::make('password'),
            'jabatan'  => 'HR Manager',
            'divisi'   => 'HRD',
            'atasan_id' => $direktur->id,
        ]);
        $hrd->syncRoles(['HR / Manager']);

        // ======================================================
        // 6. SPV
        // ======================================================
        $spv = User::firstOrCreate(['email' => 'spv@zedcore.test'], [
            'name'     => 'Naldi',
            'password' => Hash::make('password'),
            'jabatan'  => 'Supervisor IT',
            'divisi'   => 'IT',
            'atasan_id' => $direktur->id,
        ]);
        $spv->syncRoles(['SPV']);

        // ======================================================
        // 7. Employee (Staff Biasa)
        // ======================================================
        $employee = User::firstOrCreate(['email' => 'edwan@zedcore.test'], [
            'name'     => 'Edwan',
            'password' => Hash::make('password'),
            'jabatan'  => 'Staff IT',
            'divisi'   => 'IT',
            'atasan_id' => $spv->id,
        ]);
        $employee->syncRoles(['Employee']);

        $this->command->info('✅ Semua role dan user berhasil dibuat!');
        $this->command->table(
            ['Email', 'Role', 'Level'],
            [
                ['admin@zedcore.test',   'Super Admin',  8],
                ['ceo@zedcore.test',     'CEO',          7],
                ['direktur@zedcore.test','Direktur',     6],
                ['ida@zedcore.test',     'HR / Manager', 4],
                ['spv@zedcore.test',     'SPV',          3],
                ['edwan@zedcore.test',   'Employee',     2],
            ]
        );

        // Seed konfigurasi rantai approval default
        $this->call(ApprovalChainConfigSeeder::class);
    }
}
