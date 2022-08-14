<?php

namespace App\Services\Senders;

use App\Models\Tournaments\Tournament;

interface SenderInterface
{
    public function send(string $user, string $message) : void;

    public function sendAll(string $message) : void;

    public function sendPlayersOfTournament(Tournament $tournament, string $message) : void;
}
