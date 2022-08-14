<?php

namespace App\Services\Tournament;

use App\Services\Senders\SenderInterface;

class Tournament
{
    public function __construct(
        private SenderInterface $sender,
    )
    {
    }

    public function create(string $name, string $start, int $userCreatorId) : \App\Models\Tournaments\Tournament
    {
        $tournament = new \App\Models\Tournaments\Tournament();

        $tournament->setAttribute('name', $name);
        $tournament->setAttribute('tournament_start', $start);
        $tournament->setAttribute('user_id', $userCreatorId);
        $tournament->save();

        $this->sender->sendAll(Notifies::createTournament($tournament));

        return $tournament;
    }

    public function start(int $tournamentId) : void
    {

    }
}
