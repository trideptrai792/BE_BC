<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('contacts')->insert([
            [
                'user_id' => 2,
                'name' => 'Nguyễn Văn A',
                'email' => 'vana@example.com',
                'phone' => '0909090909',
                'content' => 'Tư vấn giúp tôi về sản phẩm whey',
                'reply_id' => 0,
                'created_at' => now(),
                'created_by' => 1,
                'status' => 1
            ]
        ]);
    }
}
