<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comment extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * Get the user who made the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author');
    }

    /**
     * Get the answer that the comment refers to.
     */
    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }

    /**
     * Get the question that the comment refers to.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    } 
    
    /**
     * Get the votes of the comment.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

     /**
     * Get the content of the comment.
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
     * Get the notification this comment generated
     */
    public function notification(): HasOne
    {
        return $this->hasOne(Notification::class);
    }
}
