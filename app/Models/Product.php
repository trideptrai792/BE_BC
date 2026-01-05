<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'thumbnail',
        'content',
        'description',
        'price_buy',
        'stock',
        'created_by',
        'updated_by',
        'status',
    ];
    protected $casts = [
        'stock' => 'integer',
        'price_buy' => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stores()
    {
        return $this->hasMany(ProductStore::class);
    }

    public function productAttributes() {
    return $this->hasMany(\App\Models\ProductAttribute::class);
}

 public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
        public function variants()   { return $this->hasMany(ProductVariant::class); }
    public function variantValues()
    {
        return $this->hasManyThrough(
            ProductVariantValue::class,
            ProductVariant::class,
            'product_id',            // FK trên variants
            'product_variant_id',    // FK trên variant_values
            'id',
            'id'
        );
    }
    
}
