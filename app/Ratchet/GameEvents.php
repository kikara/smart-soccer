<?php

namespace App\Ratchet;

class GameEvents
{
    private Game $game;
    private array $currentRoundState;

    public function __construct(Game $game)
    {
        $this->game = clone $game;
        $this->currentRoundState = $this->game->getCurrentRound()->getState();
    }

    public static function getGameEvents(Game $game): array
    {
        $gameEvents = new self($game);
        return $gameEvents->getEvents();
    }

    private function getEvents(): array
    {
        return [
            'is_table_busy' => $this->isTableBusy(),
            'is_new_round' => $this->isNewRound(),
            'goal_scored' => $this->goalScored(),
            'goal_missed' => $this->goalMissed(),
            'goal_count' => $this->goalCountContinious(),
            'goal_scored_count' => $this->goalScoredCount(),
        ];
    }

    private function isNewRound(): bool
    {
        if ($this->game->isGameStarted()) {
            if ($this->currentRoundState['blue_count'] === 0 && $this->currentRoundState['red_count'] === 0) {
                return true;
            }
        }
        return false;
    }

    private function isTableBusy(): bool
    {
        return ! $this->game->isGameStarted() && $this->game->isBusy();
    }

    private function goalScored()
    {
        return $this->game->getCurrentRound()->goalTrack->getGoalScored();
    }

    private function goalMissed()
    {
        return $this->game->getCurrentRound()->goalTrack->getGoalMissed();
    }

    /**
     * @return int Какой подряд(непрерывно) идет мяч забитый, пропущенный
     */
    private function goalCountContinious()
    {
        return $this->game->getCurrentRound()->goalTrack->getGoalCount();
    }

    private function goalScoredCount()
    {
        return $this->game->getCurrentRound()->goalTrack->getScoredCount();
    }
}
