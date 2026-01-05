<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'phone' => '0123456789',
                'username' => 'admin',
                'password' => Hash::make('123456'),
                'roles' => 'admin',
                'avatar' => null,
            ],
            [
                'name' => 'Customer A',
                'email' => 'customer@example.com',
                'phone' => '0987654321',
                'username' => 'customer',
                'password' => Hash::make('123456'),
                'roles' => 'customer',
                'avatar' => null,
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                $user + [
                    'created_by' => 1,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
