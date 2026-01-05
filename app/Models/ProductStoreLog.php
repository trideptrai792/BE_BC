<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStoreLog extends Model
{
    protected $table = 'product_store_logs';

    protected $fillable = [
        'product_id',
        'type',
        'qty',
        'note',
        'user_id',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'qty' => 'integer',
        'user_id' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
