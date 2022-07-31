<?php

namespace App\Http\Controllers\Game;

class GoalCount
{
    private array $rounds;

    public function __construct($rounds)
    {
        $this->rounds = $rounds;
    }

    public static function getAccountOfGame($rounds): array
    {
        $counter = new self($rounds);
        return $counter->getAccountSortedByUserId();
    }

    private function getAccountSortedByUserId(): array
    {
        $result = [];
        foreach ($this->rounds as $round) {
            $count = $result[$round['winner']] ?? 0;
            $result[$round['winner']] = array_sum([$count, 1]);
        }
        return $result;
    }
}
