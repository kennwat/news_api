<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;

class NewsPolicy
{
    public function viewAny(?User $user, News $news): bool
    {
        return true;
    }

    public function view(?User $user, News $news): bool
    {
        // Owner can always see ALL their news
        if ($user && $user->id === $news->user_id) {
            return true;
        }

        // Anyone can see ONLY visible news
        return $news->is_visible === true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, News $news): bool
    {
        return $user->id === $news->user_id;
    }

    public function delete(User $user, News $news): bool
    {
        return $user->id === $news->user_id;
    }

    public function restore(User $user, News $news): bool
    {
        return $user->id === $news->user_id;
    }

    public function forceDelete(User $user, News $news): bool
    {
        return $user->id === $news->user_id;
    }
}
