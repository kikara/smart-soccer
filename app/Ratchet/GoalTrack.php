<?php

namespace App\Ratchet;

/**
 * Отслеживание кто забил, кто пропустил, и сколько подряд забил или пропустил
 */
class GoalTrack
{
    private int $goalScoredCount = 0;
    private int $goalMissedCount = 0;

    private array $stack = [];

    public function updateScore(int $scoredUserID, int $missedUserID): void
    {
        $this->stack[] = [
            'scored' => $scoredUserID,
            'missed' => $missedUserID,
        ];
    }

    public function getGoalScoredUserId(): int
    {
        $item = $this->getCurrentItem();
        return $item['scored'];
    }

    public function getGoalMissedId(): int
    {
        $item = $this->getCurrentItem();
        return $item['missed'];
    }

    public function getGoalCount(): int
    {
        $currentItem = $this->getCurrentItem();

        $count = 0;
        $reversed = array_reverse($this->stack);
        foreach ($reversed as $item) {
            if ($currentItem['scored'] !== $item['scored']) {
                break;
            }
            $count++;
        }
        return $count;
    }

    /**
     * Счет того кто забил
     */
    public function setScoredCount($count): void
    {
        $this->goalScoredCount = (int) $count;
    }

    public function getScoredCount(): int
    {
        return $this->goalScoredCount;
    }

    public function setMissedCount($count): void
    {
        $this->goalMissedCount = (int) $count;
    }

    public function getMissedCount(): int
    {
        return $this->goalMissedCount;
    }

    public function deleteLastGoal(): void
    {
        array_pop($this->stack);
    }

    private function getCurrentItem(): ?array
    {
        if (! empty($this->stack)) {
            return $this->stack[array_key_last($this->stack)];
        }
        return ['scored' => 0, 'missed' => 0];
    }
}
