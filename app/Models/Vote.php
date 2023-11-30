<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        switch($this->type){
            case 'QUESTION':
                return $this->question();
            case 'ANSWER':
                return $this->answer();
            case 'COMMENT':
                return $this->comment();
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

    //TODO: Função que lê content_type e retorna o id content correspondente
}
