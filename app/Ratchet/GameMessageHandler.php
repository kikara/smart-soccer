<?php

namespace App\Ratchet;

use App\Models\GameSettingTemplate;
use GuzzleHttp\Exception\GuzzleException;


class GameMessageHandler
{
    private HttpClient $client;
    protected Game $game;
    protected MessageRegistry $messageRegistry;

    public function __construct()
    {
        $this->client = new HttpClient();
        $this->game = new Game();
        $this->messageRegistry = MessageRegistry::getInstance();
    }

    public function handleMessage(array $data): void
    {
        $action = $data['cmd'] . 'Action';

        if (method_exists($this, $action)) {
            $this->$action($data);
        }

        $this->messageRegistry->setPayload($this->game->getState());

        if ($this->game->isGameOver()) {
            $this->game = new Game();
        }
    }

    private function countAction(array $data): void
    {
        if (! in_array($data['value'], ['red', 'blue'], true)) {
            return;
        }

        if ($this->game->isGameStarted() && $this->game->isGameNotOver()) {

            $this->game->score($data['value']);

            if ($this->game->isRoundEnd()) {

                $this->game->checkForGameOver();

                $this->game->setNewGameRound();
            }
        }

        if ($this->game->isGameOver()) {
            $this->client->sendToSaveGame($this->game->getState());
        }
    }

    private function resetAction(): void
    {
        if ($this->game->isGameStarted()) {
            $this->game->reset();
        }
    }

    private function resetLastGoalAction(): void
    {
        if ($this->game->isGameStarted()) {
            $this->game->resetLastGoal();
        }
    }

    private function startAction(): void
    {
        if ($this->game->isBusy()) {
            $this->game->startGame();
        }
    }

    private function gameOverAction()
    {
        $this->game->gameOver();
    }

    /**
     * @throws GuzzleException|\JsonException
     */
    private function prepareAction(array $data): void
    {
        if ($this->game->isGameStarted() || $this->game->isBusy()) {
            return;
        }

        $templateRow = $this->client->getSettings($data['t_id']);

        $fromUserID = $this->client->getUserIdByTelegramChatId($data['f']);

        $recipientUserID = $this->client->getUserIdByTelegramChatId($data['r']);

        if (! $fromUserID || ! $recipientUserID || empty($templateRow)) {
            throw new \RuntimeException('prepare data not loaded');
        }

        $this->client->setOccupation($this->game, $fromUserID);

        $toSide = GameSettingTemplate::oppositeSide($templateRow['side']);

        $round = $this->game->getCurrentRound();

        $round->setGamers([
            $templateRow['side'] => $fromUserID,
            $toSide => $recipientUserID,
        ]);

        $this->game->setSideChange((bool) $templateRow['side_change'])
            ->setGameSettingTemplate($data['t_id'])
            ->setGameMode($templateRow['mode'])
            ->setBusy();
    }
}
