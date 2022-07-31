<?php

namespace App\Ratchet;

class Round
{
    public const MAX_COUNT = 10;

    private int $blueCount = 0;
    private int $redCount = 0;

    private int $bluGamerID = 0;
    private int $redGamerID = 0;

    private int $winnerID = 0;

    private bool $roundEnd = false;

    public function setGamers($blue, $red)
    {
        $this->bluGamerID = (int) $blue;
        $this->redGamerID = (int) $red;
    }

    public function getWinnerID(): int
    {
        return $this->winnerID;
    }

    public function getBlueGamerID(): int
    {
        return $this->bluGamerID;
    }

    public function getRedGamerID(): int
    {
        return $this->redGamerID;
    }

    public function incrementBlue()
    {
        if ($this->blueCount < self::MAX_COUNT) {
            $this->blueCount++;
        }
        if ($this->blueCount === self::MAX_COUNT) {
            $this->winnerID = $this->bluGamerID;
            $this->roundEnd = true;
        }
    }

    public function test()
    {
        $this->blueCount = 9;
    }

    public function incrementRed()
    {
        if ($this->redCount < self::MAX_COUNT) {
            $this->redCount++;
        }
        if ($this->redCount === self::MAX_COUNT) {
            $this->winnerID = $this->redGamerID;
            $this->roundEnd = true;
        }
    }

    public function reset()
    {
        $this->blueCount = 0;
        $this->redCount = 0;
    }

    public function getState(): array
    {
        return [
            'blue_gamer_id' => $this->bluGamerID,
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

    private function checkRoundEnd(int $count)
    {
        $this->roundEnd = $count === self::MAX_COUNT;
    }
}
