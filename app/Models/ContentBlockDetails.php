<?php

namespace App\Models;

use Database\Factories\ContentBlockDetailsFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

#[UseFactory(ContentBlockDetailsFactory::class)]
class ContentBlockDetails extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $fillable = [
        'content_block_id',
        'text_content',
        'image_path',
        'image_alt_text',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'position' => 'integer',
        ];
    }

    public $translatable = ['text_content', 'image_alt_text'];

    // Relations
    public function contentBlock(): BelongsTo
    {
        return $this->belongsTo(ContentBlock::class);
    }
}
