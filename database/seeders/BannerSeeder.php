<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('banners')->insert([
            [
                'name' => 'Banner Whey',
                'image' => '/banner/banner1.jpg',
                'link' => '/products/whey',
                'position' => 'slideshow',
                'sort_order' => 1,
                'description' => 'Khuyến mãi Whey Protein',
                'created_at' => now(),
                'created_by' => 1,
                'status' => 1
            ],
            [
                'name' => 'Banner Vitamin',
                'image' => '/banner/banner2.jpg',
                'link' => '/products/vitamins',
                'position' => 'ads',
                'sort_order' => 2,
                'description' => 'Vitamin giá tốt',
                'created_at' => now(),
                'created_by' => 1,
                'status' => 1
            ]
        ]);
    }
}
