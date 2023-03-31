<?php

namespace App\Services;

use App\Resources\GameRepository;

class GameService
{
    public function __construct(
        protected GameRepository $gameResource
    )
    {
    }

    public function lastGames()
    {
        $lastGames = $this->gameResource->getLastGames();

        dd($lastGames);
    }
}
