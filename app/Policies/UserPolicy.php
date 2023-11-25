<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Question;

use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    /** 
     * Determine wether the user is accessing 
     * something related to himself.
     */
    public function self(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    /**
     * Determine wether the user is accessing 
     * something related to himself or if 
     * the user is an admin.
     */
    public function selfOrAdmin(User $user, User $model): bool
    {
        return ($user->type === "Admin" || $user->id === $model->id);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return ($user->type === "Admin" || $user->id === $model->id);
    }
}
