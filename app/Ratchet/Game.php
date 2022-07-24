<?php

namespace App\Ratchet;

class Game
{
    private int $blueCount = 0;
    private int $redCount = 0;
    private int $totalTime = 0;
    private bool $isGameStarted = false;

    private int $bluGamer = 0;
    private int $redGamer = 0;


    public static function newGame()
    {
        return new self();
    }

    public function setGamers($blue, $red)
    {
        $this->bluGamer = (int) $blue;
        $this->redGamer = (int) $red;
    }

    public function incrementBlueTeam()
    {
        $this->blueCount++;
    }

    public function incrementRedTeam()
    {
        $this->redCount++;
    }

    public function startGame($message)
    {
        $msg = json_decode($message, true);
        $this->bluGamer = (int) $msg['blue-gamer'];
        $this->redGamer = (int) $msg['red-gamer'];
        $this->isGameStarted = true;
    }

    public function stopGame()
    {
        $this->isGameStarted = false;
    }

    public function getState()
    {
        return [
            'blue' => $this->blueCount,
            'red' => $this->redCount,
            'total_time' => $this->totalTime,
            'game_started' => $this->isGameStarted,
            'blue_gamer' => $this->bluGamer,
            'red_gamer' => $this->redGamer,
        ];
    }

    public function reset()
    {
        $this->blueCount = 0;
        $this->redCount = 0;
    }
}