<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameUser extends Model
{
    use HasFactory;

    protected $table = 'game_user';

    protected $guarded = [];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
