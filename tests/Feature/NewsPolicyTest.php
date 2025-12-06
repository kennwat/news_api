<?php

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows anyone to view visible news', function () {
    $author = User::factory()->create();
    $news = News::factory()->create([
        'user_id' => $author->id,
        'is_visible' => true,
        'published_at' => now()->subDay(),
    ]);

    // Unauthenticated can view
    $response = $this->getJson("/api/news/{$news->slug}");
    $response->assertOk();

    // Other user can view
    $otherUser = User::factory()->create();
    $response = $this->actingAs($otherUser, 'sanctum')
        ->getJson("/api/news/{$news->slug}");
    $response->assertOk();
});

it('prevents non-owners from viewing hidden news', function () {
    $author = User::factory()->create();
    $news = News::factory()->create([
        'user_id' => $author->id,
        'is_visible' => false,
    ]);

    // Unauthenticated cannot view
    $response = $this->getJson("/api/news/{$news->slug}");
    $response->assertForbidden();

    // Other user cannot view
    $otherUser = User::factory()->create();
    $response = $this->actingAs($otherUser, 'sanctum')
        ->getJson("/api/news/{$news->slug}");
    $response->assertForbidden();
});

it('allows owner to view their hidden news', function () {
    $author = User::factory()->create();
    $news = News::factory()->create([
        'user_id' => $author->id,
        'is_visible' => false,
    ]);

    $response = $this->actingAs($author, 'sanctum')
        ->getJson("/api/news/{$news->slug}");

    $response->assertOk();
});

it('allows any authenticated user to create news', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/news', [
            'title' => ['en' => 'Test Title', 'de' => 'Test Titel'],
            'short_description' => ['en' => 'Test description here', 'de' => 'Test Beschreibung hier'],
            'published_at' => now()->toDateTimeString(),
        ]);

    $response->assertOk();
});

it('prevents unauthenticated users from creating news', function () {
    $response = $this->postJson('/api/news', [
        'title' => ['en' => 'Test', 'de' => 'Test'],
        'short_description' => ['en' => 'Test', 'de' => 'Test'],
    ]);

    $response->assertUnauthorized();
});

it('allows owner to update their news', function () {
    $author = User::factory()->create();
    $news = News::factory()->create(['user_id' => $author->id]);

    $response = $this->actingAs($author, 'sanctum')
        ->patchJson("/api/news/{$news->slug}", [
            'title' => ['en' => 'Updated Title', 'de' => 'Aktualisierter Titel'],
            'short_description' => ['en' => 'Updated description', 'de' => 'Aktualisierte Beschreibung'],
        ]);

    $response->assertOk();
});

it('prevents non-owner from updating news', function () {
    $author = User::factory()->create();
    $otherUser = User::factory()->create();
    $news = News::factory()->create(['user_id' => $author->id]);

    $response = $this->actingAs($otherUser, 'sanctum')
        ->patchJson("/api/news/{$news->slug}", [
            'title' => ['en' => 'Hacked', 'de' => 'Gehackt'],
        ]);

    $response->assertForbidden();
});

it('allows owner to delete their news', function () {
    $author = User::factory()->create();
    $news = News::factory()->create(['user_id' => $author->id]);

    $response = $this->actingAs($author, 'sanctum')
        ->deleteJson("/api/news/{$news->slug}");

    $response->assertNoContent();
});

it('prevents non-owner from deleting news', function () {
    $author = User::factory()->create();
    $otherUser = User::factory()->create();
    $news = News::factory()->create(['user_id' => $author->id]);

    $response = $this->actingAs($otherUser, 'sanctum')
        ->deleteJson("/api/news/{$news->slug}");

    $response->assertForbidden();
});

it('allows owner to restore their deleted news', function () {
    $author = User::factory()->create();
    $news = News::factory()->create(['user_id' => $author->id]);
    $news->delete();

    $response = $this->actingAs($author, 'sanctum')
        ->patchJson("/api/news/{$news->id}/restore");

    $response->assertOk();
});

it('prevents non-owner from restoring deleted news', function () {
    $author = User::factory()->create();
    $otherUser = User::factory()->create();
    $news = News::factory()->create(['user_id' => $author->id]);
    $news->delete();

    $response = $this->actingAs($otherUser, 'sanctum')
        ->patchJson("/api/news/{$news->id}/restore");

    $response->assertForbidden();
});

it('allows owner to force delete their news', function () {
    $author = User::factory()->create();
    $news = News::factory()->create(['user_id' => $author->id]);
    $news->delete();

    $response = $this->actingAs($author, 'sanctum')
        ->deleteJson("/api/news/{$news->id}/force");

    $response->assertNoContent();
});

it('prevents non-owner from force deleting news', function () {
    $author = User::factory()->create();
    $otherUser = User::factory()->create();
    $news = News::factory()->create(['user_id' => $author->id]);
    $news->delete();

    $response = $this->actingAs($otherUser, 'sanctum')
        ->deleteJson("/api/news/{$news->id}/force");

    $response->assertForbidden();
});
