<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'slug' => fake()->slug(3),
            'title' => $this->translations(
                ['de', 'en'],
                [fake('de_DE')->realText(30), fake()->realText(30)]
            ),
            'short_description' => $this->translations(
                ['de', 'en'],
                [fake('de_DE')->realText(70), fake()->realText(70)]
            ),
            'image_preview_path' => fake()->boolean(70)
                ? 'news/demo/preview-'.fake()->numberBetween(1, 50).'.jpg'
                : null,
            'published_at' => fake()->boolean(80)
                ? fake()->dateTimeBetween('-30 days', 'now')
                : null,
            'is_visible' => fake()->boolean(85),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => null,
            'is_visible' => false,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'is_visible' => true,
        ]);
    }
}
