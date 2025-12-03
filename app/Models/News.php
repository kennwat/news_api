<?php

namespace App\Models;

use Database\Factories\NewsFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

#[UseFactory(NewsFactory::class)]
class News extends Model
{
    use HasTranslations, SoftDeletes;

    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'short_description',
        'image_preview_path',
        'published_at',
        'is_visible',
    ];

    public array $translatable = ['title', 'short_description'];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_visible' => 'boolean',
        ];
    }

    // Relations
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contentBlocks(): HasMany
    {
        return $this->hasMany(ContentBlock::class)->orderBy('position');
    }

    // Scopes
    public function scopeVisible($query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function scopePublished($query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
