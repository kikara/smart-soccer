<?php

namespace App\Models\Tournaments;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Tournament extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user() : Relation
    {
        return $this->belongsTo(User::class);
    }

    public function players() : Relation
    {
        return $this->belongsToMany(User::class);
    }

    public function games() : Relation
    {
        return $this->hasMany(TournamentTable::class);
    }

    public function status() : Relation
    {
        return $this->belongsTo(TournamentStatus::class, 'tournament_status_id');
    }
}
