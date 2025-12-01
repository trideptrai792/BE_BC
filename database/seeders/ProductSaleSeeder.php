<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSaleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_sales')->insert([
            [
                'name' => 'Giảm giá Whey Gold',
                'product_id' => 1,
                'price_sale' => 990000,
                'date_begin' => now(),
                'date_end' => now()->addDays(7),
                'created_at' => now(),
                'created_by' => 1,
                'status' => 1
            ],
            [
                'name' => 'Khuyến mãi Vitamin C',
                'product_id' => 7,
                'price_sale' => 150000,
                'date_begin' => now(),
                'date_end' => now()->addDays(15),
                'created_at' => now(),
                'created_by' => 1,
                'status' => 1
            ]
        ]);
    }
}
