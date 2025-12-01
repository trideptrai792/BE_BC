<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'slug'      => $this->slug,
            'thumbnail' => $this->thumbnail,
            'excerpt'   => $this->excerpt,
            'content'   => $this->content,
            'status'    => $this->status,
            'created_at'=> $this->created_at,
        ];
    }
}
