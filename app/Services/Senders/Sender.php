<?php

namespace App\Services\Senders;

use App\Models\Tournaments\Tournament;
use App\Models\User;

abstract class Sender implements SenderInterface
{
    abstract public function send(string $user, string $message): void;

    public function sendAll(string $message): void
    {
        $users = User::all(['login']);

        foreach($users as $user) {
            $this->send($user->getAttribute('login'), $message);
        }
    }

    public function sendPlayersOfTournament(Tournament $tournament, string $message): void
    {
        foreach($tournament->players() as $player) {
            $this->send($player->getAttribute('login'), $message);
        }
    }
}
