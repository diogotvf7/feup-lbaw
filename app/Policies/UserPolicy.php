<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Question;

use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    /** 
     * Determine whether users are accessing 
     * something related to themselves.
     */
    public function self(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    /**
     * Determine whether the users are accessing 
     * something related to themselves or if 
     * the users are an admin.
     */
    public function selfOrAdmin(User $user, User $model): bool
    {
        return ($user->type === "Admin" || $user->id === $model->id);
    }

    /**
     * Determine whether users can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return ($user->type === "Admin" || $user->id === $model->id);
    }
}
