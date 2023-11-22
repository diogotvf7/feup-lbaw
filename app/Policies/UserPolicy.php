<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Question;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

    /**
     * Determine whether the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return Auth::check();
    }

    //    /**
    //     * Determine whether the user can view the model.
    //     */
    //    public function view(User $user, User $model): bool
    //    {
    //        //
    //    }

    //    /**
    //     * Determine whether the user can create models.
    //     */
    //    public function create(User $user): bool
    //    {
    //        //
    //    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return ($user->type === "Admin" || $user->id === $model->id);
    }

    //    /**
    //     * Determine whether the user can delete the model.
    //     */
    //    public function delete(User $user, User $model): bool
    //    {
    //        //
    //    }

    //    /**
    //     * Determine whether the user can restore the model.
    //     */
    //    public function restore(User $user, User $model): bool
    //    {
    //        //
    //    }

    //    /**
    //     * Determine whether the user can permanently delete the model.
    //     */
    //    public function forceDelete(User $user, User $model): bool
    //    {
    //        //
    //    }
    public function questions(User $user): bool
    {
        return Auth::check();
    }
}
