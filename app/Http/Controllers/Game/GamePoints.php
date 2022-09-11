<?php

namespace App\Http\Controllers\Game;

class GamePoints
{
    public const START_POINTS = 1000;
    private const COEF = 1;

    public static function scorePoints($redPlayerScore, $bluePlayerScore, $winner)
    {
        $myWinChance = round(1 / (1 + pow(10, ($bluePlayerScore - $redPlayerScore) / 400)), 5);
        if ($winner === 1) {
            $points = round(self::COEF * (32 * (1 - $myWinChance)), 0);
        } else {
            $points = round(self::COEF * (32 * (0 - $myWinChance)), 0);
        }
        return [$redPlayerScore += $points, $bluePlayerScore -= $points];
    }
}
