<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'stock',
        'is_active',
        'image',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function values()
    {
        return $this->hasMany(ProductVariantValue::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_variant_values', 'product_variant_id', 'attribute_id')
                    ->withPivot('attribute_value_id');
    }
}
