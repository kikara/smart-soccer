<?php

namespace App\Ratchet;

use App\Models\GameSettingTemplate;

class Game
{
    public const TIME_DELAY = 10;
    public const GAME_WIN_COUNT = 2;
    public int $lastAccountedGoalBlue;
    public int $lastAccountedGoalRed;

    private \DateTime $dateTime;
    private int $startGameTime = 0;
    private int $endGameTime = 0;

    private int $gameSettingTemplateID = 0;
    private bool $isSideChange = false;
    private string $mode = '';

    private bool $isBusy = false;
    private bool $isGameStarted = false;
    private bool $isGameOver = false;

    private int $winnerID = 0;

    private int $tableOccupationID = 0;

    /**
     * @var Round[]
     */
    private array $rounds = [];
    private int $currentRound = 0;

    public function __construct()
    {
        $this->lastAccountedGoalBlue = time();
        $this->lastAccountedGoalRed = time();
        $this->addRound(new Round());
        $this->dateTime = new \DateTime();
    }

    public static function newGame(): Game
    {
        return new self();
    }

    public function incrementIndexRound()
    {
        $this->currentRound++;
    }

    public function addRound(Round $round)
    {
        $this->rounds[] = $round;
    }

    public function getCurrentRound(): Round
    {
        return $this->rounds[$this->currentRound];
    }

    public function isRoundEnd(): bool
    {
        return $this->getCurrentRound()->getRoundEnd();
    }

    public function isSideChange(): bool
    {
        return $this->isSideChange;
    }

    public function incrementBlueTeam()
    {
        $this->getCurrentRound()->incrementBlue();
    }

    public function incrementRedTeam()
    {
        $this->getCurrentRound()->incrementRed();
    }

    public function startGame()
    {
        $this->startGameTime = time();
        $this->dateTime->setTimestamp($this->startGameTime);
        $this->isGameStarted = true;
    }

    public function isGameStarted(): bool
    {
        return $this->isGameStarted;
    }

    public function stopGame()
    {
        $this->isGameStarted = false;
    }

    public function getState(): array
    {
        $res = [
            'game_started' => $this->isGameStarted,
            'mode' => $this->mode,
            'is_busy' => $this->isBusy,
            'start_time' => $this->startGameTime,
            'current_round' => $this->currentRound,
            'is_side_change' => $this->isSideChange,
            'game_over' => $this->isGameOver,
            'game_winner_id' => $this->winnerID,
            'dateTime' => $this->dateTime->format('U'),
            'round' => $this->getRoundState(),
            'events' => $this->getEvents(),
            'rounds' => $this->getRounds(),
        ];
        if ($this->isGameOver) {
            $res['total_time'] = $this->getTotalTime();
            $res['template_id'] = $this->gameSettingTemplateID;
            $res['table_occupation_id'] = $this->tableOccupationID;
        }
        return $res;
    }

    public function setGameMode(string $mode)
    {
        $this->mode = $mode;
    }

    public function setSideChange()
    {
        $this->isSideChange = true;
    }

    public function reset()
    {
        $this->getCurrentRound()->reset();
    }

    public function setBusy()
    {
        $this->isBusy = true;
    }

    public function isBusy(): bool
    {
        return $this->isBusy;
    }

    private function getRoundState(): array
    {
        return $this->getCurrentRound()->getState();
    }

    public function isGameNotOver(): bool
    {
        return ! $this->isGameOver;
    }

    public function checkForGameOver()
    {
        if (count($this->rounds) >= self::GAME_WIN_COUNT) {
            $gamersCountRound['blue'] = 0;
            $gamersCountRound['red'] = 0;
            $blueGamer = $this->getCurrentRound()->getBlueGamerID();
            foreach ($this->rounds as $round) {
                if ($blueGamer === $round->getWinnerID()) {
                    $gamersCountRound['blue'] += 1;
                } else {
                    $gamersCountRound['red'] += 1;
                }
                if ($gamersCountRound['blue'] === self::GAME_WIN_COUNT) {
                    $this->winnerID = $blueGamer;
                    $this->gameOver();
                    return;
                }
                if ($gamersCountRound['red'] === self::GAME_WIN_COUNT) {
                    $this->winnerID = $this->getCurrentRound()->getRedGamerID();
                    $this->gameOver();
                    return;
                }
            }
        }
    }

    public function setGameSettingTemplate($templateID)
    {
        $this->gameSettingTemplateID = (int) $templateID;
    }

    private function getRounds(): array
    {
        $result = [];
        foreach ($this->rounds as $round) {
            $result[] = $round->getState();
        }
        return $result;
    }

    private function gameOver()
    {
        $this->isGameOver = true;
        $this->endGameTime = time();
    }

    private function getTotalTime(): int
    {
        return $this->endGameTime - $this->startGameTime;
    }

    private function getEvents()
    {
        return GameEvents::getGameEvents($this);
    }

    public function setTableOccupationID($id)
    {
        $this->tableOccupationID = (int) $id;
    }

    public function getTableOccupationID($id): int
    {
        return $this->tableOccupationID;
    }

    public function isTimeCheck(string $side): bool
    {
        $now = time();
        $lastTime = GameSettingTemplate::isBlueSide($side) ? $this->lastAccountedGoalBlue : $this->lastAccountedGoalRed;
        $diff = $now - $lastTime;
        if ($diff > self::TIME_DELAY) {
            if (GameSettingTemplate::isBlueSide($side)) {
                $this->lastAccountedGoalBlue = $now;
            } else {
                $this->lastAccountedGoalRed = $now;
            }
            return true;
        }
        return false;
    }
}
