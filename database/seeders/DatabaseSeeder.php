<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        DB::table('roles')->insertOrIgnore([
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Staff'],
            ['id' => 3, 'name' => 'Applicant'],
        ]);

        // User Statuses
        DB::table('user_statuses')->insertOrIgnore([
            ['id' => 1, 'name' => 'Active'],
            ['id' => 2, 'name' => 'Inactive'],
            ['id' => 3, 'name' => 'Suspended'],
        ]);

        // Default Admin User
        DB::table('users')->insertOrIgnore([
            'first_name'     => 'Admin',
            'last_name'      => 'User',
            'email'          => 'admin@example.com',
            'password'       => Hash::make('password123'),
            'role_id'        => 1,
            'user_status_id' => 1,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }
}
