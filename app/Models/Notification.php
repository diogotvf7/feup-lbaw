<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    // The attributes that are mass assignable.
    protected $fillable = [
        'seen'
    ];

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * Get the user who receives the notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the answer this notification relates to
     */
    public function answer(): BelongsTo
    {
        if ($this->type == "ANSWER")
            return $this->belongsTo(Answer::class);
    }

    /**
     * Get the upvote this notification relates to
     */
    public function upvote(): BelongsTo
    {
        if ($this->type == "UPVOTE")
            return $this->belongsTo(Vote::class);
    }

    /**
     * Get the question id this notification indirectly relates to
     */
    public function relatedQuestionId()
    {
        if ($this->type == "UPVOTE") {
            if ($this->upvote->type == "ANSWER") return $this->upvote->answer->question_id;
            else if ($this->upvote->type == "QUESTION") return $this->upvote->question_id;
        } else if ($this->type == "ANSWER") {
            return $this->answer->question_id;
        }
    }
}
