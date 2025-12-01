<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderDetailSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('order_details')->insert([
            [
                'order_id' => 1,
                'product_id' => 1,
                'price' => 1250000,
                'qty' => 1,
                'amount' => 1250000,
                'discount' => 0
            ]
        ]);
    }
}
    