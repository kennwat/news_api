<?php

namespace App\Http\Requests\News;

use App\Enums\BlockTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // News fields
            'title' => ['sometimes', 'array'],
            'title.de' => ['sometimes', 'string', 'min:3', 'max:255'],
            'title.en' => ['sometimes', 'string', 'min:3', 'max:255'],
            'short_description' => ['sometimes', 'array'],
            'short_description.de' => ['sometimes', 'string', 'min:10', 'max:500'],
            'short_description.en' => ['sometimes', 'string', 'min:10', 'max:500'],
            // slug is not updatable
            'image_preview_path' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'is_visible' => ['boolean'],

            // Content blocks - if provided, must have at least 1 block (prevent accidental clearing)
            'content_blocks' => ['sometimes', 'array', 'min:1'],
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
            'title.required' => 'The title field is required.',
            'title.array' => 'The title must be a valid translatable object.',
            'title.de.required' => 'The German title is required.',
            'title.en.required' => 'The English title is required.',
            'short_description.required' => 'The short description field is required.',
            'short_description.array' => 'The short description must be a valid translatable object.',
            'content_blocks.min' => 'At least one content block is required when updating content blocks.',
            'content_blocks.*.type.required' => 'Each content block must have a type.',
            'content_blocks.*.type.enum' => 'Invalid content block type.',
            'content_blocks.*.details.required' => 'Each content block must have at least one detail.',
            'content_blocks.*.details.min' => 'Each content block must have at least one detail.',
        ];
    }
}
