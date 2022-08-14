<?php

namespace App\Models\Tournaments;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class TournamentTable extends Model
{
    use HasFactory;

    public function game() : Relation
    {
        return $this->hasOne(Game::class);
    }

    public function tournament() : Relation
    {
        return $this->belongsTo(Tournament::class);
    }

    public function player() : Relation
    {
        return $this->belongsTo(User::class);
    }
}
