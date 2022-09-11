<?php

namespace App\Taskers;

use App\Http\Controllers\Game\GamePoints;
use App\Models\Game;
use App\Models\UserRating;

class UserRatingCalculator
{
    public static function calculate()
    {
        $games = Game::all()?->toArray();
        $userPoints = [];
        foreach ($games as $game) {
            $winner = $game['gamerOne'] === $game['winner'] ? 2 : 1;
            $scorePoints = GamePoints::scorePoints(
                $userPoints[$game['gamerTwo']] ?? GamePoints::START_POINTS,
                $userPoints[$game['gamerOne']] ?? GamePoints::START_POINTS,
                $winner);
            $userPoints[$game['gamerTwo']] = $scorePoints[0];
            $userPoints[$game['gamerOne']] = $scorePoints[1];
            UserRating::updateOrCreate(
                ['user_id' => $game['gamerOne']],
                ['rating' => $userPoints[$game['gamerOne']]],
            );
            UserRating::updateOrCreate(
                ['user_id' => $game['gamerTwo']],
                ['rating' => $userPoints[$game['gamerTwo']]],
            );
        }
    }
}
