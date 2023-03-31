<?php

namespace App\Ratchet;

/**
 * Отслеживание кто забил, кто пропустил, и сколько подряд забил или пропустил
 */
class GoalTrack
{
    private array $stack = [];

    public function put(array $scored, array $missed): void
    {
        $this->stack[] = [
            'scored' => $scored,
            'missed' => $missed,
        ];
    }

    public function getLastScoredSide(): string
    {
        return (string) $this->getParameterValue('scored', 'side');
    }

    public function getGoalScoredUserId(): int
    {
        return $this->getParameterValue('scored', 'user_id');
    }

    public function getGoalMissedUserId(): int
    {
        return $this->getParameterValue('missed', 'user_id');
    }

    public function getGoalCount(): int
    {
        $currentItem = $this->getCurrentItem();

        if (! $currentItem) {
            return 0;
        }

        $count = 0;
        $reversed = array_reverse($this->stack);
        foreach ($reversed as $item) {
            if ($currentItem['scored']['user_id'] !== $item['scored']['user_id']) {
                break;
            }
            $count++;
        }
        return $count;
    }

    public function getScoredCount(): int
    {
        return $this->getParameterValue('scored', 'score');
    }

    public function getMissedCount(): int
    {
        return $this->getParameterValue('missed', 'score');
    }

    public function deleteLastGoal(): void
    {
        array_pop($this->stack);
    }

    protected function getParameterValue(string $parameter, string $property): int|string
    {
        $item = $this->getCurrentItem();
        if ($item) {
            return $item[$parameter][$property];
        }
        return 0;
    }

    protected function getCurrentItem(): ?array
    {
        if (! empty($this->stack)) {
            return $this->stack[array_key_last($this->stack)];
        }
        return null;
    }
}
