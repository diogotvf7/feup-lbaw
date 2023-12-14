<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

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

}
