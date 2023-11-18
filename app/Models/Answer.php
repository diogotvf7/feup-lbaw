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
     * Get the body of the answer.
     */
    public function body(): HasOne
    {
        return $this->contentVersions()->one()->ofMany('date', 'max');
    }
}
