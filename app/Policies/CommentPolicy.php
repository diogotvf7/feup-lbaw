<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can view any comments.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the comment.
     */
    public function view(User $user, Comment $comment): bool
    {
        //
    }

    /**
     * Determine whether the user can create comments.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the comment.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->type === "Admin" || $user->id === $comment->author;
    }

    /**
     * Determine whether the user can delete the comment.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->type === "Admin" || $user->id === $comment->author;
    }

    /**
     * Determine whether the user can restore the comment.
     */
    public function restore(User $user, Comment $comment): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the comment.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        //
    }
}
