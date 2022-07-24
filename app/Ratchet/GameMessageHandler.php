<?php

namespace App\Ratchet;

class GameMessageHandler
{
    private const BLUE_TEAM = 'blue';
    private const READ_TEAM = 'red';
    private const START_GAME = 'start';

    private array $msg;

    public function __construct($message)
    {
        $this->msg = json_decode($message, true) ?? [];
    }

    public static function handle(Game &$game, $message)
    {
        $handler = new self($message);
        if ($handler->isGameStarted()) {
            $game->startGame($message);
            return;
        }
        if ($handler->isReset()) {
            $game->reset();
            return;
        }
        if ($handler->isCountMessage()) {
            if ($handler->isBlueIncrementGoal()) {
                $game->incrementBlueTeam();
            } else if ($handler->isRedIncrementGoal()) {
                $game->incrementRedTeam();
            }
        }
    }

    private function isReset()
    {
        return $this->msg['reset'] ?? false;
    }

    private function isCountMessage(): bool
    {
        return $this->msg['count'] === self::BLUE_TEAM || $this->msg['count'] === self::READ_TEAM;
    }

    private function isBlueIncrementGoal(): bool
    {
        return $this->msg['count'] === self::BLUE_TEAM;
    }

    private function isRedIncrementGoal(): bool
    {
        return $this->msg['count'] === self::READ_TEAM;
    }

    private function isGameStarted(): bool
    {
        return $this->msg['start'] ?? false;
    }
}