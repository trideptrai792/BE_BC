<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
   return [
        'id' => $this->id,
        'name' => $this->name,
        'slug' => $this->slug,
        'price' => number_format($this->price_buy, 2),
       'thumbnail' => asset($this->thumbnail),
        'content' => $this->content,
        'images' => ProductImageResource::collection($this->images),  // Trả về danh sách hình ảnh
        'created_at' => $this->created_at,
    ];
    }
}
