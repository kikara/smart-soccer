<?php

namespace App\Ratchet;

class GameEvents
{
    private Game $game;
    private array $currentRoundState;

    public static function getGameEvents(Game $game): array
    {
        return (new self($game))->getEvents();
    }

    public function __construct(Game $game)
    {
        $this->game = $game;
        $this->currentRoundState = $this->game->getCurrentRound()->getState();
    }

    private function getEvents(): array
    {
        return [
            'is_table_busy' => $this->isTableBusy(),
            'is_new_round' => $this->isNewRound(),
            'goal_scored' => $this->goalScored(),
            'goal_missed' => $this->goalMissed(),
            'goal_count' => $this->goalCountContinuous(),
            'goal_scored_count' => $this->goalScoredCount(),
            'opponent-score' => $this->goalMissedCount(),
        ];
    }

    private function isNewRound(): bool
    {
        return $this->game->isGameStarted()
            && $this->currentRoundState['blue_count'] === 0
            && $this->currentRoundState['red_count'] === 0;
    }

    private function isTableBusy(): bool
    {
        return ! $this->game->isGameStarted() && $this->game->isBusy();
    }

    private function goalScored(): int
    {
        return $this->game->getCurrentRound()->goalTrack->getGoalScoredUserId();
    }

    private function goalMissed(): int
    {
        return $this->game->getCurrentRound()->goalTrack->getGoalMissedId();
    }

    /**
     * @return int Какой подряд(непрерывно) идет мяч забитый, пропущенный
     */
    private function goalCountContinuous(): int
    {
        return $this->game->getCurrentRound()->goalTrack->getGoalCount();
    }

    private function goalScoredCount(): int
    {
        return $this->game->getCurrentRound()->goalTrack->getScoredCount();
    }

    private function goalMissedCount(): int
    {
        return $this->game->getCurrentRound()->goalTrack->getMissedCount();
    }
}
