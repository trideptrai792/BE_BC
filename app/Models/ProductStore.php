<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStore extends Model
{
    protected $table = 'product_stores';

    protected $fillable = [
        'product_id',
        'price_root',
        'qty',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'price_root' => 'float',
        'qty' => 'integer',
        'status' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

