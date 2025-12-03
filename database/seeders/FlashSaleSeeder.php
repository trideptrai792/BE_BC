<?php

namespace Database\Seeders;

use App\Models\FlashSale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlashSaleSeeder extends Seeder
{
    /** 
     * Run the database seeds.
     */
    public function run(): void
    {
         FlashSale::create([
            'product_id'      => 1,               // ID thực trong bảng products
            'flash_price'     => 1000000,
            'discount_percent'=> 0,
            'sold'            => 4115,
            'discount_label'  => null,
            'badge_left'      => 'FREESHIP',
            'badge_right'     => 'TẶNG QUÀ',
            'benefit_1'       => 'Miễn phí giao hàng',
            'benefit_2'       => 'Tặng kèm bình lắc',
            'start_at'        => now(),
            'end_at'          => now()->addDays(27),
            'status'          => 1,
            'sort_order'      => 1,
        ]);
    }
}
