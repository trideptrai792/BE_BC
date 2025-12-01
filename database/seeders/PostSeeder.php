<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('posts')->in  sert([
            [
                'topic_id' => 1,
                'title' => 'Lợi ích của Whey Protein',
                'slug' => Str::slug('Lợi ích của Whey Protein'),
                'image' => '/images/post1.jpg',
                'content' => 'Whey protein giúp phục hồi cơ bắp nhanh chóng...',
                'description' => 'Bài viết phân tích whey',
                'post_type' => 'post',
                'created_at' => now(),
                'created_by' => 1,
                'status' => 1
            ],
            [
                'topic_id' => 2,
                'title' => 'Cách tập ngực đúng cách cho người mới',
                'slug' => Str::slug('Cách tập ngực đúng cách'),
                'image' => '/images/post2.jpg',
                'content' => 'Bài tập ngực cơ bản gồm...',
                'description' => 'Hướng dẫn tập gym',
                'post_type' => 'post',
                'created_at' => now(),
                'created_by' => 1,
                'status' => 1
            ]
        ]);
    }
}
