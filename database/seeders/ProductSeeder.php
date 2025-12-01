<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   public function run(): void
    {
        $products = [
            ['Whey Gold Standard', 1, 1250000, 'product_image4.png'],
            ['Rule 1 R1 Whey', 1, 1090000, 'product_image5.png'],
            ['Dymatize ISO 100', 1, 1690000, 'product_image6.png'],
            ['Serious Mass 12lbs', 2, 1550000, 'product_image7.png'],
            ['Mutant Mass', 2, 1390000, 'product_image8.png'],
            ['Mass Tech Extreme', 2, 1890000, 'product_image9.png'],
            ['Vitamin C 1000mg', 3, 190000, 'product_image10.png'],
            ['Vitamin D3 + K2', 3, 350000, 'product_vitamin1.png'],
            ['One A Day Multivitamin', 3, 420000, 'product_vitamin2.png'],
            ['Whey Gold Standard', 1, 1250000, 'product_image4.png'],
            ['Rule 1 R1 Whey', 1, 1090000, 'product_image5.png'],
            ['Dymatize ISO 100', 1, 1690000, 'product_image6.png'],
            ['Serious Mass 12lbs', 2, 1550000, 'product_mass1.png'],
            ['Mutant Mass', 2, 1390000, 'product_image8.png'],
            ['Mass Tech Extreme', 2, 1890000, 'product_mass2.png'],
            ['Vitamin C 1000mg', 3, 190000, 'product_image10.png'],
            ['Vitamin D3 + K2', 3, 350000, 'product_vitamin1.png'],
            ['One A Day Multivitamin', 3, 420000, 'product_vitamin2.png'],
            ['Whey Gold Standard', 1, 1250000, 'product_image11.png'],
            ['Rule 1 R1 Whey', 1, 1090000, 'product_image12.png'],
            ['Dymatize ISO 100', 1, 1690000, 'product_image13.png'],
            ['Serious Mass 12lbs', 2, 1550000, 'product_image14.png'],
            ['Mutant Mass', 2, 1390000, 'product_mass3.png'],
            ['Mass Tech Extreme', 2, 1890000, 'product_mass4.png'],
        ];

        foreach ($products as $p) {
            DB::table('products')->insert([
                'name' => $p[0],
                'slug' => Str::slug($p[0]),
                'category_id' => $p[1],
                'thumbnail' => '/images/' . $p[3],  // Sử dụng hình ảnh thực tế từ thư mục public/images
                'content' => $p[0] . ' là sản phẩm chất lượng cao.',
                'price_buy' => $p[2],
                'status' => 1,
                'created_at' => now(),
                'created_by' => 1
            ]);
        }
    }
}
