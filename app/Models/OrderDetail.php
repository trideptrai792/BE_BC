<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    protected $table = 'order_details';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'qty',
        'amount',
        'discount',
    ];

    // ✅ thêm dòng này
    protected $appends = ['quantity'];

    // ✅ thêm accessor này
    public function getQuantityAttribute(): int
    {
        return (int) ($this->attributes['qty'] ?? 0);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
