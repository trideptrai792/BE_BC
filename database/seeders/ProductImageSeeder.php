<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 9; $i++) {
            DB::table('product_images')->insert([
                'product_id' => $i,
                'image' => '/images/noimage.png',
                'alt' => null,
                'title' => null
            ]);
        }
    }
}
