<?php

namespace App\Policies;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AnswerPolicy
{
    /**
     * Determine whether the user can view any answers.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the answer.
     */
    public function view(User $user, Answer $answer): bool
    {
        //
    }

    /**
     * Determine whether the user can create answers.
     */
    public function create(User $user, Answer $answer): bool
    {
        $question = Question::findOrFail($answer->question_id);
        return $user->id !== $question->author;
    }

    /**
     * Determine whether the user can update the answer.
     */
    public function update(User $user, Answer $answer): bool
    {
        return $user->id === $answer->author;
    }

    /**
     * Determine whether the user can delete the answer.
     */
    public function delete(User $user, Answer $answer): bool
    {
        return $user->id === $answer->author;
    }

    /**
     * Determine whether the user can restore the answer.
     */
    public function restore(User $user, Answer $answer): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the answer.
     */
    public function forceDelete(User $user, Answer $answer): bool
    {
        //
    }
}
