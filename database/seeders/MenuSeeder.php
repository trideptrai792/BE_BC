<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('menus')->insert([
            [
                'name'       => 'Trang chủ',
                'link'       => '/',
                'type'       => 'custom',
                'position'   => 'mainmenu',
                'sort_order' => 1,
                'created_at' => now(),
                'created_by' => 1,
                'status'     => 1
            ],
            [
                'name'       => 'Whey Protein',
                'link'       => '/products/whey',
                'type'       => 'category',
                'position'   => 'mainmenu',
                'sort_order' => 2,
                'created_at' => now(),
                'created_by' => 1,
                'status'     => 1
            ],
            [
                'name'       => 'Mass Gainer',
                'link'       => '/products/mass',
                'type'       => 'category',
                'position'   => 'mainmenu',
                'sort_order' => 3,
                'created_at' => now(),
                'created_by' => 1,
                'status'     => 1
            ],
            [
                'name'       => 'Giới thiệu',
                'link'       => '/gioi-thieu',
                'type'       => 'page',
                'position'   => 'footermenu',
                'sort_order' => 1,
                'created_at' => now(),
                'created_by' => 1,
                'status'     => 1,
            ]
        ]);
    }
}
