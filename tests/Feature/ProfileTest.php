<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can view own profile', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/profile');

    $response->assertOk()
        ->assertJson([
            'id' => $user->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
});

it('cannot view profile without authentication', function () {
    $response = $this->getJson('/api/profile');

    $response->assertUnauthorized();
});

it('can update profile name', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'user@example.com',
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->patchJson('/api/profile', [
            'name' => 'New Name',
        ]);

    $response->assertOk()
        ->assertJson([
            'name' => 'New Name',
            'email' => 'user@example.com',
        ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'New Name',
    ]);
});

it('can update profile email', function () {
    $user = User::factory()->create([
        'email' => 'old@example.com',
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->patchJson('/api/profile', [
            'email' => 'new@example.com',
        ]);

    $response->assertOk()
        ->assertJson([
            'email' => 'new@example.com',
        ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'email' => 'new@example.com',
    ]);
});

it('can update profile password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('old-password'),
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->patchJson('/api/profile', [
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123',
        ]);

    $response->assertOk();

    // Verify new password works
    $this->assertTrue(
        \Hash::check('new-password123', $user->fresh()->password)
    );
});

it('cannot update profile with invalid email', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->patchJson('/api/profile', [
            'email' => 'invalid-email',
        ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('cannot update profile with existing email', function () {
    User::factory()->create(['email' => 'existing@example.com']);
    $user = User::factory()->create(['email' => 'user@example.com']);

    $response = $this->actingAs($user, 'sanctum')
        ->patchJson('/api/profile', [
            'email' => 'existing@example.com',
        ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('cannot update profile without authentication', function () {
    $response = $this->patchJson('/api/profile', [
        'name' => 'New Name',
    ]);

    $response->assertUnauthorized();
});
