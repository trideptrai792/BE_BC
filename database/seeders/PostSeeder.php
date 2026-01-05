<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'topic_id'   => 1,
                'title'     => 'Lợi ích của Whey Protein',
                'slug'      => Str::slug('Lợi ích của Whey Protein'),
                'thumbnail' => '/images/post1.jpg',
                'excerpt'   => 'Bài viết phân tích whey',
                'content'   => 'Whey protein giúp phục hồi cơ bắp nhanh chóng...',
                'post_type' => 'post',
                'created_by'=> 1,
                'status'    => 1,
            ],
            [
                'topic_id'   => 2,
                'title'     => 'Cách tập ngực đúng cách cho người mới',
                'slug'      => Str::slug('Cách tập ngực đúng cách cho người mới'),
                'thumbnail' => '/images/post2.jpg',
                'excerpt'   => 'Hướng dẫn tập gym',
                'content'   => 'Bài tập ngực cơ bản gồm...',
                'post_type' => 'post',
                'created_by'=> 1,
                'status'    => 1,
            ],
        ];

        foreach ($posts as $post) {
            DB::table('posts')->updateOrInsert(
                ['slug' => $post['slug']],
                $post + [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

