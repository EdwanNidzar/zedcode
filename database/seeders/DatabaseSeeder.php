<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat Roles
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $hrManager = Role::firstOrCreate(['name' => 'HR / Manager']);
        $employee = Role::firstOrCreate(['name' => 'Employee']);

        // Buat Super Admin
        $user = User::firstOrCreate([
            'email' => 'admin@zedcore.test',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole($superAdmin);
    }
}
