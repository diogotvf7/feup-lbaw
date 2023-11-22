<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentVersion extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Get the annexes that the content version refers to.
     */
    public function annexes(): HasMany
    {
        return $this->hasMany(Annex::class);
    }

    /**
     * Get the question that the content version refers to.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the answer that the content version refers to.
     */
    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }
    
}
