<?php

use App\Models\User;

it('allows any user to view list of users', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', User::class))->toBeTrue();
});

it('allows user to view only their own profile', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    expect($user->can('view', $user))->toBeTrue();
    expect($user->can('view', $otherUser))->toBeFalse();
});

it('allows user to update only their own profile', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    expect($user->can('update', $user))->toBeTrue();
    expect($user->can('update', $otherUser))->toBeFalse();
});

it('prevents any user from creating new users', function () {
    $user = User::factory()->create();

    expect($user->can('create', User::class))->toBeFalse();
});

it('prevents any user from deleting users', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    expect($user->can('delete', $user))->toBeFalse();
    expect($user->can('delete', $otherUser))->toBeFalse();
});

it('prevents any user from restoring users', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    expect($user->can('restore', $user))->toBeFalse();
    expect($user->can('restore', $otherUser))->toBeFalse();
});

it('prevents any user from force deleting users', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    expect($user->can('forceDelete', $user))->toBeFalse();
    expect($user->can('forceDelete', $otherUser))->toBeFalse();
});
