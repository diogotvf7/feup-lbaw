<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Answer extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $fillable = ['id'];

    /**
     * Get the content versions of the answer.
     */
    public function contentVersions(): HasMany
    {
        return $this->hasMany(ContentVersion::class);
    }

    /**
     * Get the user who made the answer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author');
    }

    /**
     * Get the comments made to that answer.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the question that the answer refers to.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the votes of the answer.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Get the upvotes of the question.
     */
    public function upvotes(): HasMany
    {
        return $this->hasMany(Vote::class)->where('is_upvote', '=', 'TRUE');
    }

    /**
     * Get the downvotes of the question.
     */
    public function downvotes(): HasMany
    {
        return $this->hasMany(Vote::class)->where('is_upvote', '=', 'FALSE');
    }

    /**
     * Get the difference between number of upvotes and downvotes on the question.
     */
    public function getVoteBalanceAttribute()
    {
        return count($this->upvotes) - count($this->downvotes);
    }

    /**
     * Get the most recent version of the answer.
     */
    public function updatedVersion(): HasOne
    {
        return $this->contentVersions()->one()->ofMany('date', 'max');
    }

    /**
     * Get the first version of the answer.
     */
    public function firstVersion(): HasOne
    {
        return $this->contentVersions()->one()->ofMany('date', 'min');
    }

    /**
     * Get the notification this answer generated
     */
    public function notification(): HasOne
    {
        return $this->hasOne(Notification::class);
    }

    /** 
     * Return the date of the creation of the answer.
     */
    public function getCreatedAtAttribute(): string
    {
        return $this->firstVersion->date;
    }

    /**
     * Return the date of the last edit of the answer.
     */
    public function getUpdatedAtAttribute(): string
    {
        return $this->updatedVersion->date;
    }

    protected $appends = ['vote_balance', 'created_at', 'updated_at'];
}
