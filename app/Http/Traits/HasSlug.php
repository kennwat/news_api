<?php

namespace App\Http\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    public static function generateUniqueSlug(string $title, string $modelClass): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while ($modelClass::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
