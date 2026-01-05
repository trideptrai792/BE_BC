<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
        protected $fillable = ['cart_id','product_id','variant_key','variant_json','qty'];
    protected $casts = ['variant_json' => 'array'];

    public function cart() {
        return $this->belongsTo(Cart::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
