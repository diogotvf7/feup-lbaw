<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * Get the content versions of the question.
     */
    public function contentVersions(): HasMany
    {
        return $this->hasMany(ContentVersion::class);
    }

    /**
     * Get the tags of the question.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get the user who made the question.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'author');
    }

    /**
     * Get the answers made to the question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Get the correct answer of the question.
     */
    public function correctAnswer(): HasOne
    {
        return $this->hasOne(Answer::class)->withDefault();
    }

    /**
     * Get the comments made to the question.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the votes of the question.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Get the most recent of the question.
     */
    public function updatedVersion(): HasOne
    {
        return $this->contentVersions()->one()->ofMany('date', 'max');
    }

    /**
     *  Get the oldest verison of a question
     */

    public function firstVersion(): HasOne
    {
        return $this->contentVersions()->one()->ofMany('date', 'min');
    }
}
