<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vote extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $fillable = [
        'is_upvote',
        'user_id',
        'question_id',
        'answer_id',
        'comment_id',
        'type'
    ];

    /**
     * Get the user who made the vote.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the content of the vote.
     */
    public function content(): BelongsTo
    {
        switch ($this->type) {
            case 'QUESTION':
                return $this->question();
            case 'ANSWER':
                return $this->answer();
            default:  return NULL;
        }
    }

    /**
     * Get the question of the vote.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the comment of the vote.
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    /**
     * Get the answer of the vote.
     */
    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }

    /**
     * Get the notification the upvote generated
     */
    public function notification(): HasOne
    {
        if ($this->is_upvote) {
            return $this->HasOne(Notification::class);
        }
    }
}
