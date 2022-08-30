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

    public function setGamers($blue, $red)
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

    public function incrementBlue()
    {
        $this->goalTrack->updateScore($this->blueGamerID, $this->redGamerID);
        if ($this->blueCount < self::MAX_COUNT) {
            $this->blueCount++;
        }
        if ($this->blueCount === self::MAX_COUNT) {
            $this->winnerID = $this->blueGamerID;
            $this->roundEnd = true;
        }
        $this->goalTrack->setScoredCount($this->blueCount);
        $this->goalTrack->setMissedCount($this->redCount);
    }

    public function test()
    {
        $this->blueCount = 9;
    }

    public function incrementRed()
    {
        $this->goalTrack->updateScore($this->redGamerID, $this->blueGamerID);
        if ($this->redCount < self::MAX_COUNT) {
            $this->redCount++;
        }
        if ($this->redCount === self::MAX_COUNT) {
            $this->winnerID = $this->redGamerID;
            $this->roundEnd = true;
        }
        $this->goalTrack->setScoredCount($this->redCount);
        $this->goalTrack->setMissedCount($this->blueCount);
    }

    public function reset()
    {
        $this->blueCount = 0;
        $this->redCount = 0;
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
}
