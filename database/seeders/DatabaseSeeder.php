<?php

namespace Database\Seeders;

use App\Enums\BlockTypeEnum;
use App\Models\ContentBlock;
use App\Models\ContentBlockDetails;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Main test user
        $mainUser = User::factory()->create([
            'name' => 'main author',
            'email' => 'test@example.com',
        ]);

        $this->createNewsWithBlocks($mainUser, 10);

        // Additional users with news
        User::factory(4)
            ->create()
            ->each(fn ($user) => $this->createNewsWithBlocks($user, rand(3, 7)));

        $this->command->info('💪Created users with news and content blocks');
    }

    protected function createNewsWithBlocks(User $user, int $newsCount): void
    {
        News::factory($newsCount)
            ->for($user, 'author')
            ->state(new Sequence(
                ['is_visible' => true, 'published_at' => fake()->dateTimeBetween('-30 days', 'now')],
                ['is_visible' => true, 'published_at' => fake()->dateTimeBetween('-7 days', 'now')],
                ['is_visible' => false, 'published_at' => null],
            ))
            ->create()
            ->each(function ($news) {
                // Block 1: Text
                $textBlock = ContentBlock::factory()->text()->for($news)->create(['position' => 1]);
                ContentBlockDetails::factory()->textOnly()->for($textBlock, 'contentBlock')->create();

                // Block 2: Image
                $imageBlock = ContentBlock::factory()->image()->for($news)->create(['position' => 2]);
                ContentBlockDetails::factory()->imageOnly()->for($imageBlock, 'contentBlock')->create();

                // Block 3: Text + Image (alternating right/left)
                $position = 3;
                ContentBlock::factory()
                    ->count(2)
                    ->for($news)
                    ->state(new Sequence(
                        ['type' => BlockTypeEnum::TextImageRight->value, 'position' => $position++],
                        ['type' => BlockTypeEnum::TextImageLeft->value, 'position' => $position++],
                    ))
                    ->create()
                    ->each(function ($block) {
                        ContentBlockDetails::factory()->for($block, 'contentBlock')->create();
                    });

                // Randomly add a slider
                if (rand(0, 1)) {
                    $sliderBlock = ContentBlock::factory()->slider()->for($news)->create(['position' => $position]);

                    // Add 3-5 images for the slider
                    ContentBlockDetails::factory()
                        ->count(rand(3, 5))
                        ->sliderImage() // ✅ використовує існуючий state
                        ->for($sliderBlock, 'contentBlock')
                        ->state(new Sequence(
                            fn (Sequence $sequence) => ['position' => $sequence->index]
                        ))
                        ->create();
                }
            });
    }
}
