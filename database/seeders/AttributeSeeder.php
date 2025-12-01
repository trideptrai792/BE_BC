<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('attributes')->insert([
            ['name' => 'Khối lượng'],
            ['name' => 'Hương vị'],
            ['name' => 'Xuất xứ'],
            ['name' => 'Thương hiệu'],
            ['name' => 'Protein / Serving']
        ]);
    }
}
