<?php

namespace App\Http\Resources\News;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'short_description' => $this->short_description,
            'image_preview_url' => $this->image_preview_path
                ? asset('storage/'.$this->image_preview_path)
                : null,
            'published_at' => $this->published_at?->toISOString(),
            'is_visible' => $this->is_visible,
            'author' => $this->whenLoaded('author', function () {
                return [
                    'id' => $this->author->id,
                    'name' => $this->author->name,
                ];
            }),
            'content_blocks' => ContentBlockResource::collection($this->whenLoaded('contentBlocks')),
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
