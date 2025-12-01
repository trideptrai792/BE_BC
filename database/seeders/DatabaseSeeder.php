<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // Tạo người dùng mặc định (admin)
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

        // Chạy các seeder khác như ProductSeeder, CategorySeeder, v.v.
        $this->call([
            UserSeeder::class,
            ProductSeeder::class,
            CategorySeeder::class,
            BannerSeeder::class,
            ProductImageSeeder::class,
            // Thêm các seeder khác nếu cần
        ]);
    }
}
