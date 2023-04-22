<?php

namespace App\Ratchet;

use App\Models\GameSettingTemplate;

class Game
{
    public const GAME_WIN_COUNT = 2;

    private \DateTime $dateTime;
    private int $startGameTime = 0;
    private int $endGameTime = 0;

    private int $gameSettingTemplateID = 0;
    private bool $isSideChange = false;
    private string $mode = '';

    private bool $isBusy = false;
    private bool $isGameStarted = false;
    private bool $isGameOver = false;

    private int $winnerUserId = 0;

    private int $tableOccupationId = 0;

    private array $states = [];

    /**
     * @var Round[]
     */
    private array $rounds = [];
    private int $currentRound = 0;

    protected GameEvents $gameEvents;

    public function __construct()
    {
        $this->rounds[] = new Round();
        $this->dateTime = new \DateTime();
        $this->gameEvents = new GameEvents($this);
    }

    public function addRound(Round $round): void
    {
        $this->rounds[] = $round;
        $this->currentRound++;
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

    public function score(string $side): void
    {
        if (! $this->isRoundEnd()) {
            $this->getCurrentRound()->score($side);
        }
    }

    public function startGame(): void
    {
        $this->startGameTime = time();
        $this->dateTime->setTimestamp($this->startGameTime);
        $this->isGameStarted = true;
    }

    public function isGameStarted(): bool
    {
        return $this->isGameStarted;
    }

    public function stopGame(): void
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
            'game_winner_id' => $this->winnerUserId,
            'dateTime' => $this->dateTime->format('U'),
            'round' => $this->getRoundState(),
            'events' => $this->getEvents(),
            'rounds' => $this->getRounds(),
        ];
        if ($this->isGameOver) {
            $res['total_time'] = $this->getTotalTime();
            $res['template_id'] = $this->gameSettingTemplateID;
            $res['table_occupation_id'] = $this->tableOccupationId;
        }
        foreach ($this->states as $key => $state) {
            $res[$key] = $state;
        }
        $this->states = [];
        return $res;
    }

    public function setGameMode(string $mode): static
    {
        $this->mode = $mode;
        return $this;
    }

    public function setSideChange(bool $isChange): static
    {
        $this->isSideChange = $isChange;
        return $this;
    }

    public function reset(): void
    {
        $this->getCurrentRound()->reset();
    }

    public function setBusy(): void
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

    public function checkForGameOver(): void
    {
        if (count($this->rounds) < self::GAME_WIN_COUNT) {
            return;
        }

        $frequency = array_count_values(array_map(fn($item) => $item->getWinnerUserId(), $this->rounds));

        arsort($frequency);

        $winnerUserId = array_key_first($frequency);

        $count = $frequency[$winnerUserId];

        if ($winnerUserId && $count === self::GAME_WIN_COUNT) {
            $this->winnerUserId = $winnerUserId;
            $this->gameOver();
        }
    }

    public function setGameSettingTemplate($id): static
    {
        $this->gameSettingTemplateID = (int) $id;
        return $this;
    }

    public function isGameOver(): bool
    {
        return $this->isGameOver;
    }

    private function getRounds(): array
    {
        $result = [];
        foreach ($this->rounds as $round) {
            $result[] = $round->getState();
        }
        return $result;
    }

    public function gameOver(): void
    {
        $this->isGameOver = true;
        $this->endGameTime = time();
    }

    private function getTotalTime(): int
    {
        return $this->endGameTime - $this->startGameTime;
    }

    private function getEvents(): array
    {
        return $this->gameEvents->getEvents();
    }

    public function setTableOccupationId($id): void
    {
        $this->tableOccupationId = (int) $id;
    }

    public function resetLastGoal(): void
    {
        $this->getCurrentRound()->deleteLastGoal();
    }

    public function setNewGameRound(): void
    {
        if ($this->isGameOver()) {
            return;
        }

        $currentRound = $this->getCurrentRound();

        $gamers = $currentRound->getGamers();

        $temp = [];
        foreach ($gamers as $side => $gamer) {
            $temp[$side] = $gamer['user_id'];
        }

        $round = new Round();

        if ($this->isSideChange()) {
            $first = array_key_first($temp);
            $last = array_key_last($temp);
            $temp = [
                $first => $temp[$last],
                $last => $temp[$first]
            ];
        }

        $round->setGamers($temp);

        $this->addRound($round);
    }
}
