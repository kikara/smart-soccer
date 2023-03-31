<?php

namespace App\Ratchet;

class GameEvents
{
    private Game $game;
    private array $currentRoundState;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function getEvents(): array
    {
        $this->currentRoundState = $this->game->getCurrentRound()->getState();
        return [
            'is_table_busy' => $this->isTableBusy(),
            'is_new_round' => $this->isNewRound(),
            'goal_scored' => $this->goalScored(),
            'goal_missed' => $this->goalMissed(),
            'goal_count' => $this->goalCountContinuous(),
            'goal_scored_count' => $this->goalScoredCount(),
            'opponent_score' => $this->goalMissedCount(),
        ];
    }

    private function isNewRound(): bool
    {
        if (! $this->game->isGameStarted()) {
            return false;
        }

        foreach ($this->currentRoundState['gamers'] as $gamer) {
            if ($gamer['score'] !== 0) {
                return false;
            }
        }

        return true;
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
        return $this->game->getCurrentRound()->goalTrack->getGoalMissedUserId();
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
