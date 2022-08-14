<?php

namespace App\Models;

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
}
