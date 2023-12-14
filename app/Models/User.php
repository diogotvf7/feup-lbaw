<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the badges of the user.
     */
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class)->withPivot('date');
    }

    /**
     * Get the votes of the user.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Get the tags the user follows.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get the notifications the user received.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the questions made by the user.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'author');
    }

    /**
     * Get the comments made by the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'author');
    }

    /**
     * Get the answers made by the user.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'author');
    }

    /**
     * Get the users that the user follows.
     */
    public function follows(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followed_users', 'follower_id', 'followed_id');
    }

    /**
     * Get the followers of the user.
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followed_users', 'followed_id', 'follower_id');
    }

    /**
     * Get the questions that the user follows.
     */
    public function followedQuestions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'followed_questions', 'user_id', 'question_id');
    }

    /**
     * Return true if the user upvoted the question with the given id.
     */
    public function upvoted($content_type, $content_id): string
    {
        return $this->votes()
            ->where('is_upvote', true)
            ->where($content_type . '_id', $content_id)
            ->exists();
    }

    /**
     * Return true if the user downvoted the question with the given id.
     */
    public function downvoted($content_type, $content_id): string
    {
        return $this->votes()
            ->where($content_type . '_id', $content_id)
            ->where('is_upvote', false)
            ->exists();
    }

    public function voted($type, $content_id): string
    {
        $type .= '_id';
        $vote = $this->votes()->where($type, $content_id)->first();
        if ($vote) return $vote->is_upvote ? 'upvote' : 'downvote';
        return '';
    }

    public function followsQuestion($question_id): bool
    {
        return $this->followedQuestions()->where('question_id', $question_id)->exists();
    }

    public function getUnreadNotificationsAttribute()
    {
        return $this->notifications()->where('seen', 'false')->count();
    }

    protected $appends = ['unreadNotifications'];
}
