<?php

namespace App\Models;

use Database\Factories\ContentBlockFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

#[UseFactory(ContentBlockFactory::class)]
class ContentBlock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'news_id',
        'type',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'position' => 'integer',
        ];
    }

    // Relations
    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(ContentBlockDetails::class)->orderBy('position');
    }
}
