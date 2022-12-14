<?php

namespace App\Ratchet;

class GameCommand
{
    private static array $cmdCallbacks = [
        'count' => 'incrementValue',
        'reset' => 'reset',
        'start' => 'start',
        'prepare' => 'gamePrepare',
        'reset_last_goal' => 'resetLastGoal',
//        'test' => 'test',
    ];

    public static function getCallback(string $cmd)
    {
        return self::$cmdCallbacks[$cmd] ?? 'notFound';
    }
}
