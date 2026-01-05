<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        if (!DB::table('users')->where('email', 'test@example.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => '0123456789',
                'username' => 'testuser',
                'password' => Hash::make('password'),
                'roles' => 'admin',
                'avatar' => null,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'status' => 1,
            ]);
        }

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ProductStoreSeeder::class,
            BannerSeeder::class,
            ProductImageSeeder::class,
            ProductAttributeSeeder::class,
            ProductVariantSeeder::class,
            FlashSaleSeeder::class,
            PostSeeder::class,
            MenuSeeder::class,
        ]);
    }
}
