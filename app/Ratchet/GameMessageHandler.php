<?php

namespace App\Ratchet;

use App\Models\GameSettingTemplate;
use GuzzleHttp\Client;


class GameMessageHandler
{
    private array $msg;
    private string $cmd;
    private Client $client;
    private const HOST = 'http://localhost';

    public function __construct($message)
    {
        $this->msg = $message;
        $this->cmd = $this->msg['cmd'] ?? '';
        $this->client = new Client();
    }

    public static function handle(Game $game, $message)
    {
        $handler = new self($message);
        $callback = GameCommand::getCallback($handler->cmd);
        $handler->$callback($game);
    }

    public function test(Game $game)
    {
        $game->getCurrentRound()->test();
    }

    private function isTimeCheck(Game $game, string $side): bool
    {
        $now = time();
        $lastTime = GameSettingTemplate::isBlueSide($side) ? $game->lastAccountedGoalBlue : $game->lastAccountedGoalRed;
        $diff = $now - $lastTime;
        if ($diff > Game::TIME_DELAY) {
            if (GameSettingTemplate::isBlueSide($side)) {
                $game->lastAccountedGoalBlue = $now;
            } else {
                $game->lastAccountedGoalRed = $now;
            }
            return true;
        }
        return false;
    }

    private function incrementValue(Game $game)
    {
        if ($game->isGameStarted() && $game->isGameNotOver()) {
            if (! $game->isRoundEnd()) {
                $callback = GameSettingTemplate::isBlueSide($this->msg['value']) ? 'incrementBlueTeam' : 'incrementRedTeam';
                if ($this->isTimeCheck($game, $this->msg['value'])) {
                    $game->$callback();
                }
            }
            if ($game->isRoundEnd()) {
                $game->checkForGameOver();
                if ($game->isGameNotOver()) {
                    $round = new Round();
                    if ($game->isSideChange()) {
                        $round->setGamers(
                            $game->getCurrentRound()->getRedGamerID(),
                            $game->getCurrentRound()->getBlueGamerID(),
                        );
                    } else {
                        $round->setGamers(
                            $game->getCurrentRound()->getBlueGamerID(),
                            $game->getCurrentRound()->getRedGamerID(),
                        );
                    }
                    $game->addRound($round);
                    $game->incrementIndexRound();
                }
            }
        }
    }

    private function reset(Game $game)
    {
        if ($game->isGameStarted()) {
            $game->reset();
        }
    }

    private function start(Game $game)
    {
        $game->startGame();
    }

    private function gamePrepare(Game $game)
    {
        if ($game->isGameStarted() || $game->isBusy()) {
            return;
        }

        $templateRow = $this->getTemplateRowById($this->msg['t_id']);
        $fromUserID = $this->getUserIdByTelegramChatId($this->msg['f']);
        $recipientUserID = $this->getUserIdByTelegramChatId($this->msg['r']);
        if (! $fromUserID || ! $recipientUserID || ! $templateRow) {
            return;
        }
        $round = $game->getCurrentRound();
        if (GameSettingTemplate::isBlueSide($templateRow['side'])) {
            $round->setGamers($fromUserID, $recipientUserID);
        } else {
            $round->setGamers($recipientUserID, $fromUserID);
        }
        if ($templateRow['side_change']) {
            $game->setSideChange();
        }
        $game->setGameSettingTemplate($this->msg['t_id']);
        $game->setGameMode($templateRow['mode']);
        $game->setBusy();

    }

    private function notFound(Game $game)
    {
        return;
    }

    private function getUserIdByTelegramChatId($chatId)
    {
        $response = $this->client->post(self::HOST . '/api/bot/getUserIdByTelegramChatId', [
            'form_params' => ['chat_id' => $chatId],
        ]);

        if ($response->getStatusCode() !== 200) {
            return false;
        }
        $response = json_decode($response->getBody()->getContents(), true);
        return $response['data'] ? $response['user_id'] : false;
    }

    private function getTemplateRowById($templateID)
    {
        $request = $this->client->post(self::HOST . '/api/bot/getGameSettingsById',[
            'form_params' => ['template_id' => $templateID],
        ]);
        if ($request->getStatusCode() !== 200) {
            return false;
        }
        $response = $request->getBody()->getContents();
        $jsonResponse = json_decode($response, true);
        return $jsonResponse['data'] ? $jsonResponse['settings'] : false;
    }
}
