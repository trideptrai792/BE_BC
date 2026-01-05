<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
   public function toArray(Request $request): array
{
    $stock = (int) (
        $this->stores_sum_qty
        ?? ($this->relationLoaded('stores') ? $this->stores->sum('qty') : null)
        ?? ($this->stock ?? 0)
    );

    return [
        'id' => $this->id,
        'name' => $this->name,
        'slug' => $this->slug,
        'sku' => $this->sku ?? ('SKU-'.$this->id),
        'price'        => (float) $this->price_buy,
        'price_origin' => (float) $this->price_buy,
        'price_sale'   => $this->price_sale !== null ? (float) $this->price_sale : null,
        'price_display'=> $this->price_display !== null
                            ? (float) $this->price_display
                            : (float) $this->price_buy,
        'stock'     => $stock,
        'in_stock'  => $stock > 0,
        'thumbnail' => asset($this->thumbnail),
        'content' => $this->content,
        'images' => ProductImageResource::collection($this->images),
        'product_attributes' => $this->whenLoaded('productAttributes', function () {
            return $this->productAttributes->map(function ($pa) {
                return [
                    'id'             => $pa->id,
                    'attribute_id'   => $pa->attribute_id,
                    'attribute_name' => $pa->attribute->name ?? null,
                    'value'          => $pa->value,
                ];
            });
        }),
        'created_at' => $this->created_at,
    ];
}
}
