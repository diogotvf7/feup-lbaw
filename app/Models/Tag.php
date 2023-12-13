<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    // The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'description',
        'approved',
        'creator'
    ];

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * Get the questions that use the tag.
     */
    public function questions(): BelongsToMany
    {
        return $this-> belongsToMany(Question::class);
    }

    /**
     * Get the users that follow the tag.
     */
    public function usersThatFollow(): BelongsToMany
    {
        return $this-> belongsToMany(User::class,'followed_tags','tag_id','user_id');
    }
}
