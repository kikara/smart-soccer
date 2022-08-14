<?php

namespace App\Models;

use App\Models\Tournaments\TournamentTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Game extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tournament() : Relation
    {
        return $this->belongsTo(TournamentTable::class);
    }
}
