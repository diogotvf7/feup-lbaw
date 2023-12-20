<?php

namespace App\Policies;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AnswerPolicy
{
    /**
     * Determine whether the user can create answers.
     */
    public function create(User $user, Answer $answer): bool
    {
        $question = Question::findOrFail($answer->question_id);
        return $user->id !== $question->author;
    }

    /**
     * Determine whether the user can vote the answer.
     */
    public function vote(User $user, Answer $answer): Response
    {
        if ($user->id === $answer->author) {
            return Response::deny('You cannot vote your own answer!');
        }
        return Response::allow();
    }

    /**
     * Determine whether the user can update the answer.
     */
    public function update(User $user, Answer $answer): bool
    {
        return $user->type === "Admin" || $user->id === $answer->author;
    }

    /**
     * Determine whether the user can delete the answer.
     */
    public function delete(User $user, Answer $answer): bool
    {
        return $user->type === "Admin" || $user->id === $answer->author;
    }
}
