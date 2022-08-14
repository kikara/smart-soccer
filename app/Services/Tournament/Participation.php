<?php

namespace App\Services\Tournament;

use App\Models\Tournament;

class Participation
{
    private Tournament $tournament;

    public function __construct(
        private int $userId,
        int $tournamentId,
    )
    {
        $this->tournament = Tournament::find($tournamentId);
    }

    public function addPlayer() : void
    {
        $this->tournament->players()->attach($this->userId);
    }

    public function removePlayer() : void
    {
        $this->tournament->players()->detach($this->userId);
    }
}
