<?php

namespace App\Ratchet;

use App\Models\GameSettingTemplate;
use Illuminate\Support\Collection;

class Round
{
    public const MAX_COUNT = 10;
    public const TIME_DELAY = 1;

    public GoalTrack $goalTrack;

    private int $winnerUserId = 0;

    private bool $roundEnd = false;

    /**
     * @var Collection<string, RoundUserScore>
     */
    protected Collection $gamers;
    public function __construct()
    {
        $this->goalTrack = new GoalTrack();
        $this->gamers = new Collection();
    }

    public function setGamers(array $gamers): void
    {
        foreach ($gamers as $side => $userId) {
            $roundUserScore = new RoundUserScore($userId, $side);
            $this->gamers->put($side, $roundUserScore);
        }
    }

    public function score(string $side): void
    {
        $userScore = $this->gamers->get($side);

        if (! $this->available($userScore->scoreTime)) {
            return;
        }

        $userMissed = $this->gamers->get(
            GameSettingTemplate::oppositeSide($side)
        );

        $userScore->increment();

        $this->goalTrack->put(
            $userScore->toArray(),
            $userMissed->toArray()
        );

        $this->roundEnd = $userScore->score >= self::MAX_COUNT;

        if ($this->roundEnd) {
            $this->winnerUserId = $userScore->userId;
        }
    }

    public function getWinnerUserId(): int
    {
        return $this->winnerUserId;
    }

    public function reset(): void
    {
        $this->goalTrack = new GoalTrack();
        foreach ($this->gamers as $gamer) {
            $gamer->score = 0;
        }
    }

    public function getState(): array
    {
        return [
            'gamers' => $this->getGamers(),
            'winner_id' => $this->winnerUserId,
        ];
    }

    public function getRoundEnd(): bool
    {
        return $this->roundEnd;
    }

    public function deleteLastGoal(): void
    {
        $lastScoredSide = $this->goalTrack->getLastScoredSide();

        $userScore = $this->gamers->get($lastScoredSide);

        if ($userScore) {
            $userScore->decrement();
            $this->goalTrack->deleteLastGoal();
        }
    }

    public function getGamers(): array
    {
        $out = [];
        foreach ($this->gamers as $gamer) {
            $out[$gamer->side] = $gamer->toArray();
        }
        return $out;
    }

    private function available(int $scoreTime): bool
    {
        return time() - $scoreTime > self::TIME_DELAY;
    }
}
