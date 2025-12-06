<?php

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Public Access Tests
it('can list visible news without authentication', function () {
    $user = User::factory()->create();

    News::factory()->count(3)->create([
        'user_id' => $user->id,
        'is_visible' => true,
        'published_at' => now()->subDay(),
    ]);

    News::factory()->count(2)->create([
        'user_id' => $user->id,
        'is_visible' => false,
    ]);

    $response = $this->getJson('/api/news');

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

it('can view single visible news without authentication', function () {
    $user = User::factory()->create();
    $news = News::factory()->create([
        'user_id' => $user->id,
        'is_visible' => true,
        'published_at' => now()->subDay(),
    ]);

    $response = $this->getJson("/api/news/{$news->slug}");

    $response->assertOk()
        ->assertJson([
            'data' => [
                'id' => $news->id,
                'slug' => $news->slug,
            ],
        ]);
});

it('cannot view hidden news without authentication', function () {
    $user = User::factory()->create();
    $news = News::factory()->create([
        'user_id' => $user->id,
        'is_visible' => false,
    ]);

    $response = $this->getJson("/api/news/{$news->slug}");

    $response->assertForbidden();
});

it('can search news by title', function () {
    $user = User::factory()->create();

    News::factory()->create([
        'user_id' => $user->id,
        'title' => ['en' => 'Laravel News', 'de' => 'Laravel Nachrichten'],
        'is_visible' => true,
        'published_at' => now()->subDay(),
    ]);

    News::factory()->create([
        'user_id' => $user->id,
        'title' => ['en' => 'PHP Article', 'de' => 'PHP Artikel'],
        'is_visible' => true,
        'published_at' => now()->subDay(),
    ]);

    $response = $this->getJson('/api/news?search=Laravel');

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

it('can filter news by author', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    News::factory()->count(2)->create([
        'user_id' => $user1->id,
        'is_visible' => true,
        'published_at' => now()->subDay(),
    ]);

    News::factory()->create([
        'user_id' => $user2->id,
        'is_visible' => true,
        'published_at' => now()->subDay(),
    ]);

    $response = $this->getJson("/api/news?author={$user1->id}");

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

// Authenticated User Tests
it('can create news', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/news', [
            'title' => [
                'en' => 'Test News',
                'de' => 'Test Nachrichten',
            ],
            'short_description' => [
                'en' => 'Short description',
                'de' => 'Kurze Beschreibung',
            ],
            'image_preview_path' => '/path/to/image.jpg',
            'published_at' => now()->toDateTimeString(),
            'is_visible' => true,
        ]);

    $response->assertOk()
        ->assertJsonStructure([
            'data' => ['id', 'slug', 'title', 'short_description', 'is_visible'],
        ]);

    $this->assertDatabaseHas('news', [
        'user_id' => $user->id,
        'is_visible' => true,
    ]);
});

it('auto-generates slug if not provided', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/news', [
            'title' => [
                'en' => 'Auto Slug News',
                'de' => 'Auto Slug Nachrichten',
            ],
            'short_description' => [
                'en' => 'Description',
                'de' => 'Beschreibung',
            ],
            'published_at' => now()->toDateTimeString(),
        ]);

    $response->assertOk();

    expect($response->json('data.slug'))->not->toBeNull();
});

it('can create news with content blocks', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/news', [
            'title' => ['en' => 'News with Blocks', 'de' => 'Nachrichten mit Blöcken'],
            'short_description' => ['en' => 'Description', 'de' => 'Beschreibung'],
            'published_at' => now()->toDateTimeString(),
            'content_blocks' => [
                [
                    'type' => 'text',
                    'position' => 1,
                    'details' => [
                        [
                            'text_content' => ['en' => 'Content text', 'de' => 'Inhalt Text'],
                            'position' => 1,
                        ],
                    ],
                ],
            ],
        ]);

    $response->assertOk();

    $newsId = $response->json('data.id');
    $this->assertDatabaseHas('content_blocks', [
        'news_id' => $newsId,
        'type' => 'text',
    ]);
});

it('owner can see their hidden news', function () {
    $user = User::factory()->create();
    $news = News::factory()->create([
        'user_id' => $user->id,
        'is_visible' => false,
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->getJson("/api/news/{$news->slug}");

    $response->assertOk();
});

it('can update own news', function () {
    $user = User::factory()->create();
    $news = News::factory()->create([
        'user_id' => $user->id,
        'title' => ['en' => 'Old Title', 'de' => 'Alter Titel'],
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->patchJson("/api/news/{$news->slug}", [
            'title' => ['en' => 'New Title', 'de' => 'Neuer Titel'],
            'short_description' => [
                'en' => $news->getTranslation('short_description', 'en'),
                'de' => $news->getTranslation('short_description', 'de'),
            ],
        ]);

    $response->assertOk();

    $this->assertDatabaseHas('news', [
        'id' => $news->id,
        'slug' => $news->slug,
    ]);

    $updatedNews = News::find($news->id);
    expect($updatedNews->getTranslation('title', 'en'))->toBe('New Title');
    expect($updatedNews->getTranslation('title', 'de'))->toBe('Neuer Titel');
});

it('cannot update other user news', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    $news = News::factory()->create([
        'user_id' => $owner->id,
    ]);

    $response = $this->actingAs($otherUser, 'sanctum')
        ->patchJson("/api/news/{$news->slug}", [
            'title' => ['en' => 'Hacked Title', 'de' => 'Gehackter Titel'],
        ]);

    $response->assertForbidden();
});

it('can delete own news', function () {
    $user = User::factory()->create();
    $news = News::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->deleteJson("/api/news/{$news->slug}");

    $response->assertNoContent();

    $this->assertSoftDeleted('news', [
        'id' => $news->id,
    ]);
});

it('cannot delete other user news', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    $news = News::factory()->create([
        'user_id' => $owner->id,
    ]);

    $response = $this->actingAs($otherUser, 'sanctum')
        ->deleteJson("/api/news/{$news->slug}");

    $response->assertForbidden();
});

it('can toggle visibility of own news', function () {
    $user = User::factory()->create();
    $news = News::factory()->create([
        'user_id' => $user->id,
        'is_visible' => true,
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->patchJson("/api/news/{$news->slug}/toggle-visibility");

    $response->assertOk()
        ->assertJson([
            'data' => [
                'is_visible' => false,
            ],
        ]);

    $this->assertDatabaseHas('news', [
        'id' => $news->id,
        'is_visible' => false,
    ]);
});

it('can restore deleted news', function () {
    $user = User::factory()->create();
    $news = News::factory()->create([
        'user_id' => $user->id,
    ]);

    $news->delete();

    $response = $this->actingAs($user, 'sanctum')
        ->patchJson("/api/news/{$news->id}/restore");

    $response->assertOk();

    $this->assertDatabaseHas('news', [
        'id' => $news->id,
        'deleted_at' => null,
    ]);
});

it('can force delete news permanently', function () {
    $user = User::factory()->create();
    $news = News::factory()->create([
        'user_id' => $user->id,
    ]);

    $newsId = $news->id;
    $news->delete();

    $response = $this->actingAs($user, 'sanctum')
        ->deleteJson("/api/news/{$newsId}/force");

    $response->assertNoContent();

    $this->assertDatabaseMissing('news', [
        'id' => $newsId,
    ]);
});

it('deleting news cascades to content blocks', function () {
    $user = User::factory()->create();
    $news = News::factory()->create(['user_id' => $user->id]);

    $block = $news->contentBlocks()->create([
        'type' => 'text',
        'position' => 1,
    ]);

    $detail = $block->details()->create([
        'text_content' => ['en' => 'Test', 'de' => 'Test'],
        'position' => 1,
    ]);

    $this->actingAs($user, 'sanctum')
        ->deleteJson("/api/news/{$news->slug}");

    $this->assertSoftDeleted('news', ['id' => $news->id]);
    $this->assertSoftDeleted('content_blocks', ['id' => $block->id]);
    $this->assertSoftDeleted('content_block_details', ['id' => $detail->id]);
});

it('cannot create news without authentication', function () {
    $response = $this->postJson('/api/news', [
        'title' => ['en' => 'Test', 'de' => 'Test'],
        'short_description' => ['en' => 'Test', 'de' => 'Test'],
    ]);

    $response->assertUnauthorized();
});
