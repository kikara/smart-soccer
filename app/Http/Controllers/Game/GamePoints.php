<?php

namespace App\Http\Controllers\Game;

class GamePoints
{
    public const START_POINTS = 1000;
    private const COEF = 1;

    public static function scorePoints($firstPlayerScore, $secondPlayerScore, $winner): array
    {
        $myWinChance = round(1 / (1 + (10 ** (($secondPlayerScore - $firstPlayerScore) / 400))), 5);

        $variant = $winner === 1 ? 1 : 0;

        $points = round(self::COEF * (32 * ($variant - $myWinChance)));

        return [$firstPlayerScore + $points, $secondPlayerScore - $points];
    }
}
