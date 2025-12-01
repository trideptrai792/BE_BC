<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Whey Protein',
            'Mass Gainer',
            'Vitamin & Khoáng Chất',
            'Phục Hồi – BCAA',
            'Pre-workout'
        ];

        foreach ($categories as $name) {
            DB::table('categories')->insert([
                'name' => $name,
                'slug' => Str::slug($name),
                'image' => null,
                'parent_id' => 0,
                'sort_order' => 0,
                'created_at' => now(),
                'created_by' => 1,
                'status' => 1
            ]);
        }
    }
}
