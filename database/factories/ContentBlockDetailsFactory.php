<?php

namespace Database\Factories;

use App\Models\ContentBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentBlockDetailsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'content_block_id' => ContentBlock::factory(),
            'text_content' => $this->translations(
                ['de', 'en'],
                [fake('de_DE')->realText(300), fake()->realText(300)]
            ),
            'image_path' => 'news/demo/image-'.fake()->numberBetween(1, 50).'.jpg',
            'image_alt_text' => $this->translations(
                ['de', 'en'],
                [fake('de_DE')->realText(50), fake()->realText(50)]
            ),
            'position' => 0,
        ];
    }

    public function textOnly(): static
    {
        return $this->state(fn (array $attributes) => [
            'image_path' => null,
            'image_alt_text' => null,
        ]);
    }

    public function imageOnly(): static
    {
        return $this->state(fn (array $attributes) => [
            'text_content' => null,
        ]);
    }

    public function sliderImage(int $position = 0): static
    {
        return $this->state(fn (array $attributes) => [
            'text_content' => null,
            'image_path' => 'news/demo/slider-'.fake()->numberBetween(1, 50).'.jpg',
            'position' => $position,
        ]);
    }
}
