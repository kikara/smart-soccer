<?php

namespace App\Ratchet;

class RoundUserScore
{
    public int $score = 0;
    public int $scoreTime = 0;

    public function __construct(
        public int    $userId,
        public string $side
    )
    {
    }

    public function increment(): void
    {
        $this->score++;
        $this->scoreTime = time();
    }

    public function decrement(): void
    {
        if ($this->score > 0) {
            $this->score--;
        }
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'score' => $this->score,
            'side' => $this->side,
        ];
    }
}
