<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => $this->image,  // Trả về đường dẫn hình ảnh
            'alt' => $this->alt,      // Nếu bạn có trường alt, có thể trả về
            'title' => $this->title,  // Nếu bạn có trường title, có thể trả về
        ];
    }
}
