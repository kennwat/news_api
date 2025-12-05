<?php

namespace App\Http\Resources\News;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentBlockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'position' => $this->position,
            'details' => ContentBlockDetailResource::collection($this->whenLoaded('details')),
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
