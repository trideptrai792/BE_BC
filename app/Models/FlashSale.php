<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $fillable = [
        'product_id',
        'flash_price',
        'discount_percent',
        'sold',
        'discount_label',
        'badge_left',
        'badge_right',
        'benefit_1',
        'benefit_2',
        'start_at',
        'end_at',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
