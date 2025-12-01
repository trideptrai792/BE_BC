<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                'user_id' => 2,
                'name' => 'Nguyễn Văn A',
                'email' => 'vana@example.com',
                'phone' => '0909090909',
                'address' => '123 Lê Lợi, HCM',
                'note' => 'Giao buổi sáng',
                'created_at' => now(),
                'created_by' => 1,
                'status' => 1
            ]
        ]);
    }
}
