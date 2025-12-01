<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'phone' => '0123456789',
                'username' => 'admin',
                'password' => Hash::make('123456'),
                'roles' => 'admin',
                'avatar' => null,
                'created_by' => 1,
                'created_at' => now()
            ],
            [
                'name' => 'Customer A',
                'email' => 'customer@example.com',
                'phone' => '0987654321',
                'username' => 'customer',
                'password' => Hash::make('123456'),
                'roles' => 'customer',
                'avatar' => null,
                'created_by' => 1,
                'created_at' => now()
            ]
        ]);
    }
}
