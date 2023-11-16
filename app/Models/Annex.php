<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Annex extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $table = 'annexes';

    /**
     * Get the content version of the annex.
     */
    public function contentVersion(): BelongsTo
    {
        return $this->belongsTo(ContentVersion::class);
    }
}

