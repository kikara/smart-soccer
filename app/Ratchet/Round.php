<?php

namespace App\Ratchet;

class Round
{
    public const MAX_COUNT = 10;

    private int $blueCount = 0;
    private int $redCount = 0;

    private int $blueGamerID = 0;
    private int $redGamerID = 0;

    private int $winnerID = 0;

    private bool $roundEnd = false;

    public GoalTrack $goalTrack;

    public function __construct()
    {
        $this->goalTrack = new GoalTrack();
    }

    public function setGamers($blue, $red): void
    {
        $this->blueGamerID = (int) $blue;
        $this->redGamerID = (int) $red;
    }

    public function getWinnerID(): int
    {
        return $this->winnerID;
    }

    public function getBlueGamerID(): int
    {
        return $this->blueGamerID;
    }

    public function getRedGamerID(): int
    {
        return $this->redGamerID;
    }

    public function incrementBlue(): void
    {
        $this->increment('blue');
    }

    public function incrementRed(): void
    {
        $this->increment();
    }

    public function reset(): void
    {
        $this->blueCount = 0;
        $this->redCount = 0;
        $this->goalTrack = new GoalTrack();
    }

    public function getState(): array
    {
        return [
            'blue_gamer_id' => $this->blueGamerID,
            'red_gamer_id' => $this->redGamerID,
            'blue_count' => $this->blueCount,
            'red_count' => $this->redCount,
            'winner_id' => $this->winnerID,
        ];
    }

    public function getRoundEnd(): bool
    {
        return $this->roundEnd;
    }

    public function deleteLastGoal(): void
    {
        $lastScoredGamer = $this->goalTrack->getGoalScoredUserId();

        $count = $lastScoredGamer === $this->redGamerID ? 'redCount' : 'blueCount';

        if ($this->$count > 0) {
            $this->$count--;
        }

        $this->goalTrack->deleteLastGoal();
    }

    private function increment(string $value = 'red'): void
    {
        [$scored, $missed] = $this->getItems($value);

        $this->goalTrack->updateScore($this->{$scored['gamer']}, $this->{$missed['gamer']});
        if ($this->{$scored['count']} < self::MAX_COUNT) {
            $this->{$scored['count']}++;
        }
        if ($this->{$scored['count']} === self::MAX_COUNT) {
            $this->winnerID = $this->$scored['gamer'];
            $this->roundEnd = true;
        }
        $this->goalTrack->setScoredCount($this->{$scored['count']});
        $this->goalTrack->setMissedCount($this->{$missed['count']});
    }

    private function getItems(string $value): array
    {
        $items = [
            [
                'gamer' => 'redGamerID',
                'count' => 'redCount',
            ],
            [
                'gamer' => 'blueGamerID',
                'count' => 'blueCount',
            ]
        ];
        return $value === 'red' ? $items : array_reverse($items);
    }
}
