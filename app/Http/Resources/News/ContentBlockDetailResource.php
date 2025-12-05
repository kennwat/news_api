<?php

namespace App\Http\Resources\News;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentBlockDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text_content' => $this->text_content,
            'image_path' => $this->image_path,
            'image_url' => $this->image_path
                ? asset('storage/'.$this->image_path)
                : null,
            'image_alt_text' => $this->image_alt_text,
            'position' => $this->position,
        ];
    }
}
