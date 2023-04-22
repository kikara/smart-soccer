<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAudioEvent extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'parameters' => 'array'
    ];

    protected $fillable = [];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Events::class);
    }
}
