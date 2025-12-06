<?php

namespace App\Models;

use App\Policies\NewsPolicy;
use Database\Factories\NewsFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

#[UseFactory(NewsFactory::class)]
#[UsePolicy(NewsPolicy::class)]
class News extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

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

    protected static function booted(): void
    {
        static::deleting(function (News $news) {
            if (! $news->isForceDeleting()) {
                $news->contentBlocks()->get()->each(function ($block) {
                    $block->details()->delete();
                    $block->delete();
                });
            } else {
                $news->contentBlocks()->forceDelete();
            }
        });

        static::restoring(function (News $news) {
            $news->contentBlocks()->onlyTrashed()->get()->each(function ($block) {
                $block->details()->onlyTrashed()->restore();
                $block->restore();
            });
        });
    }
}
