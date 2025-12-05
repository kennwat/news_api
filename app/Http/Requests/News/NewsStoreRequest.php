<?php

namespace App\Http\Requests\News;

use App\Enums\BlockTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // News fields
            'title' => ['required', 'array'],
            'title.de' => ['required', 'string', 'min:3', 'max:255'],
            'title.en' => ['required', 'string', 'min:3', 'max:255'],
            'short_description' => ['required', 'array'],
            'short_description.de' => ['required', 'string', 'min:10', 'max:500'],
            'short_description.en' => ['required', 'string', 'min:10', 'max:500'],
            'slug' => ['sometimes', 'string', 'max:100', 'unique:news,slug'],
            'image_preview_path' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'is_visible' => ['boolean'],

            // Content blocks
            'content_blocks' => ['nullable', 'array'],
            'content_blocks.*.type' => ['required', 'string', Rule::enum(BlockTypeEnum::class)],
            'content_blocks.*.position' => ['required', 'integer', 'min:0'],

            // Content block details
            'content_blocks.*.details' => ['required', 'array', 'min:1'],
            'content_blocks.*.details.*.text_content' => ['nullable', 'array'],
            'content_blocks.*.details.*.text_content.de' => ['nullable', 'string'],
            'content_blocks.*.details.*.text_content.en' => ['nullable', 'string'],
            'content_blocks.*.details.*.image_path' => ['nullable', 'string', 'max:255'],
            'content_blocks.*.details.*.image_alt_text' => ['nullable', 'array'],
            'content_blocks.*.details.*.image_alt_text.de' => ['nullable', 'string', 'max:255'],
            'content_blocks.*.details.*.image_alt_text.en' => ['nullable', 'string', 'max:255'],
            'content_blocks.*.details.*.position' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title is required',
            'title.array' => 'The title must be an array with translations',
            'title.de.required' => 'The German title is required',
            'title.en.required' => 'The English title is required',
            'title.de.min' => 'The German title must be at least :min characters',
            'title.en.min' => 'The English title must be at least :min characters',
            'short_description.required' => 'The short description is required',
            'short_description.array' => 'The short description must be an array with translations',
            'short_description.de.required' => 'The German short description is required',
            'short_description.en.required' => 'The English short description is required',
            'short_description.de.min' => 'The German short description must be at least :min characters',
            'short_description.en.min' => 'The English short description must be at least :min characters',
            'slug.unique' => 'This slug already exists',
            'published_at.date' => 'The published date must be a valid date',

            'content_blocks.*.type.required' => 'Content block type is required',
            'content_blocks.*.type.enum' => 'Invalid content block type',
            'content_blocks.*.position.required' => 'Content block position is required',
            'content_blocks.*.details.required' => 'Content block details are required',
            'content_blocks.*.details.min' => 'Content block must have at least one detail',
        ];
    }
}
