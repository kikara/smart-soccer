<?php

namespace App\Ratchet;

use App\Models\GameSettingTemplate;
use GuzzleHttp\Exception\GuzzleException;


class GameMessageHandler
{
    private HttpClient $client;

    public function __construct()
    {
        $this->client = new HttpClient();
    }

    public function handleMessage(Game $game, array $data): void
    {
        $action = $data['cmd'] . 'Action';

        if (method_exists($this, $action)) {
            $this->$action($game, $data);
        }
    }

    private function countAction(Game $game, array $data): void
    {
        if (! in_array($data['value'], ['red', 'blue'], true)) {
            return;
        }

        if ($game->isGameStarted() && $game->isGameNotOver()) {

            $game->score($data['value']);

            if ($game->isRoundEnd()) {

                $game->checkForGameOver();

                $game->setNewGameRound();
            }
        }

        if ($game->isGameOver()) {
            $this->client->sendToSaveGame($game->getState());
        }
    }

    private function resetAction(Game $game): void
    {
        if ($game->isGameStarted()) {
            $game->reset();
        }
    }

    private function resetLastGoalAction(Game $game): void
    {
        if ($game->isGameStarted()) {
            $game->resetLastGoal();
        }
    }

    private function startAction(Game $game): void
    {
        if ($game->isBusy()) {
            $game->startGame();
        }
    }

    /**
     * @throws GuzzleException|\JsonException
     */
    private function prepareAction(Game $game, array $data): void
    {
        if ($game->isGameStarted() || $game->isBusy()) {
            return;
        }

        $templateRow = $this->client->getSettings((int) $data['t_id']);

        $fromUserID = $this->client->getUserIdByTelegramChatId((int) $data['f']);

        $recipientUserID = $this->client->getUserIdByTelegramChatId((int) $data['r']);


        $toSide = GameSettingTemplate::oppositeSide($templateRow['side']);

        $round = $game->getCurrentRound();

        $round->setGamers([
            $templateRow['side'] => $fromUserID,
            $toSide => $recipientUserID,
        ]);

        $game->setSideChange((bool) $templateRow['side_change'])
            ->setGameSettingTemplate($data['t_id'])
            ->setGameMode($templateRow['mode'])
            ->setBusy();

        $this->client->setOccupation($game, $fromUserID);
    }
}
