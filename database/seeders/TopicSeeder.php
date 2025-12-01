<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('topics')->insert([
            [
                'name' => 'Dinh dưỡng thể hình',
                'slug' => Str::slug('Dinh dưỡng thể hình'),
                'sort_order' => 1,
                'description' => 'Kiến thức bổ sung dành cho gymer',
                'created_at' => now(),
                'created_by' => 1,
                'status' => 1
            ],
            [
                'name' => 'Hướng dẫn luyện tập',
                'slug' => Str::slug('Hướng dẫn luyện tập'),
                'sort_order' => 2,
                'description' => 'Tips tập luyện',
                'created_at' => now(),
                'created_by' => 1,
                'status' => 1
            ]
        ]);
    }
}
