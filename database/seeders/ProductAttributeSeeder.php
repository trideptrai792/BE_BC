<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAttributeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_attributes')->insert([
            ['product_id' => 1, 'attribute_id' => 1, 'value' => '2.27kg'],
            ['product_id' => 1, 'attribute_id' => 2, 'value' => 'Chocolate'],
            ['product_id' => 2, 'attribute_id' => 5, 'value' => '25g Protein'],
            ['product_id' => 7, 'attribute_id' => 3, 'value' => 'USA'],
        ]);
    }
}
