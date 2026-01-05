<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variantId = DB::table('product_variants')->insertGetId([
    'product_id' => 1,
            'sku'        => 'SP-001-CHOC-227',
            'price'      => 1_250_000,
            'stock'      => 10,
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
           $attrValueIds = [
            ['attribute_id' => 1, 'value' => '2.27kg'],
            ['attribute_id' => 2, 'value' => 'Chocolate'],
            ['attribute_id' => 5, 'value' => '25g Protein'],
            ['attribute_id' => 3, 'value' => 'USA'],
        ];
        
        $variantValues = [];
        foreach ($attrValueIds as $item) {
            $valueId = DB::table('product_attribute_values')
                ->where('attribute_id', $item['attribute_id'])
                ->where('value', $item['value'])
                ->value('id');

            if ($valueId) {
                $variantValues[] = [
                    'product_variant_id' => $variantId,
                    'attribute_id'       => $item['attribute_id'],
                    'attribute_value_id' => $valueId,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];
            }
        }

        if (! empty($variantValues)) {
            DB::table('product_variant_values')->insert($variantValues);
        }
        
    }
}
