<?php

namespace Database\Factories;

use App\Enums\BlockTypeEnum;
use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentBlockFactory extends Factory
{
    public function definition(): array
    {
        return [
            'news_id' => News::factory(),
            'type' => BlockTypeEnum::Text->value,
            'position' => fake()->numberBetween(1, 10),
        ];
    }

    public function text(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => BlockTypeEnum::Text->value,
        ]);
    }

    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => BlockTypeEnum::Image->value,
        ]);
    }

    public function textImageRight(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => BlockTypeEnum::TextImageRight->value,
        ]);
    }

    public function textImageLeft(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => BlockTypeEnum::TextImageLeft->value,
        ]);
    }

    public function slider(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => BlockTypeEnum::Slider->value,
        ]);
    }
}
