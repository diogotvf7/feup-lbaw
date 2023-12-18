<?php

namespace App\Policies;

use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuestionPolicy
{
    /**
     * Determine whether the user can update the question.
     */
    public function update(User $user, Question $question): bool
    {
        return $user->type === "Admin" || $user->id === $question->author;
    }

    /**
     * Determine whether the user can delete the question.
     */
    public function delete(User $user, Question $question): bool
    {
        return $user->type === "Admin" || $user->id === $question->author;
    }

    public function vote(User $user, Question $question): Response
    {
        if ($user->id === $question->author) {
            return Response::deny('You cannot vote your own question!');
        }
        return Response::allow();
    }

    public function follow(User $user, Question $question): Response
    {
        if ($user->id === $question->author) {
            return Response::deny('You cannot follow your own question!');
        }
        return Response::allow();
    }
}
