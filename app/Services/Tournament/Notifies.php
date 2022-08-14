<?php

namespace App\Services\Tournament;

class Notifies
{
    static public function createTournament(\App\Models\Tournaments\Tournament $tournament) : string
    {
        return sprintf(
            'Создан турин "%s" пользователем %s, который пройдет %s. Для участия в нем посетите страницу турниров.',
            $tournament->getAttribute('name'),
            $tournament->user()->getModel()->getAttribute('login'),
            $tournament->getAttribute('tournament_start'),
        );
    }

    static public function startTournament(\App\Models\Tournaments\Tournament $tournament) : string
    {
        return sprintf(
            'Турнир "%s" начался!',
            $tournament->getAttribute('name'),
        );
    }
}
