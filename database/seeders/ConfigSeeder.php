<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('configs')->insert([
            'site_name' => 'Supplement Store',
            'email' => 'support@supplement.vn',
            'phone' => '0909009009',
            'hotline' => '1800-1000',
            'address' => '123 Lê Lợi, TP.HCM',
            'status' => 1
        ]);
    }
}
