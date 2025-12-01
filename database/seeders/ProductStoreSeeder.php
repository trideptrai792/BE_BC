<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductStoreSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 9; $i++) {
            DB::table('product_stores')->insert([
                'product_id' => $i,
                'price_root' => rand(500000, 2000000),
                'qty' => rand(10, 50),
                'status' => 1,
                'created_at' => now(),
                'created_by' => 1
            ]);
        }
    }
}
