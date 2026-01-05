<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAttributeSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa dữ liệu phụ thuộc trước, tránh lỗi FK khi truncate
        DB::table('product_variant_values')->delete();
        DB::table('product_attribute_values')->delete(); // dùng delete thay vì truncate để khỏi lỗi 1701

        // Đảm bảo có bản ghi attributes trước khi chèn values
        $attributes = [
            ['id' => 1, 'name' => 'Trọng lượng', 'slug' => 'trong-luong'],
            ['id' => 2, 'name' => 'Hương vị', 'slug' => 'huong-vi'],
            ['id' => 3, 'name' => 'Xuất xứ', 'slug' => 'xuat-xu'],
            ['id' => 5, 'name' => 'Hàm lượng', 'slug' => 'ham-luong'],
        ];  

        foreach ($attributes as $attr) {
            DB::table('attributes')->updateOrInsert(
                ['id' => $attr['id']],
                ['name' => $attr['name'], 'slug' => $attr['slug'], 'created_at' => now(), 'updated_at' => now()]
            );
        }

                DB::table('product_attribute_values')->insert([
            // Trọng lượng
            ['attribute_id' => 1, 'value' => '2.27kg'],
            ['attribute_id' => 1, 'value' => '1kg'],
            ['attribute_id' => 1, 'value' => '5lbs'],

            // Hương vị
            ['attribute_id' => 2, 'value' => 'Chocolate'],
            ['attribute_id' => 2, 'value' => 'Vanilla'],
            ['attribute_id' => 2, 'value' => 'Cookies & Cream'],
            ['attribute_id' => 2, 'value' => 'Strawberry'],

            // Hàm lượng
            ['attribute_id' => 5, 'value' => '25g Protein'],
            ['attribute_id' => 5, 'value' => '30g Protein'],

            // Xuất xứ
            ['attribute_id' => 3, 'value' => 'USA'],
            ['attribute_id' => 3, 'value' => 'EU'],
        ]);

    }
}
