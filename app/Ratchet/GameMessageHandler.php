<?php

namespace App\Ratchet;

use App\Models\GameSettingTemplate;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


class GameMessageHandler
{
    private array $msg;
    private string $cmd;
    private Client $client;
    private const HOST = 'http://localhost';

    public function __construct(array $message)
    {
        $this->msg = $message;
        $this->cmd = $this->msg['cmd'] ?? '';
        $this->client = new Client();
    }

    public static function handle(Game $game, array $message): void
    {
        $handler = new self($message);
        custom_log($handler->cmd, true);
        $callback = GameCommand::getCallback($handler->cmd);
        $handler->$callback($game);
    }

//    public function test(Game $game): void
//    {
//        $game->getCurrentRound()->test();
//    }

    private function incrementValue(Game $game): void
    {
        if ($game->isGameStarted() && $game->isGameNotOver()) {
            if (! $game->isRoundEnd()) {
                $callback = GameSettingTemplate::isBlueSide($this->msg['value']) ? 'incrementBlueTeam' : 'incrementRedTeam';
                if ($game->isTimeCheck($this->msg['value'])) {
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
        if ($game->isGameOver()) {
            $this->sendToSaveGame($game);
        }
    }

    private function reset(Game $game): void
    {
        if ($game->isGameStarted()) {
            $game->reset();
        }
    }

    private function resetLastGoal(Game $game): void
    {
        if ($game->isGameStarted()) {
            $game->resetLastGoal();
        }
    }

    private function start(Game $game): void
    {
        $game->startGame();
    }

    /**
     * @throws GuzzleException
     * @throws \JsonException
     */
    private function gamePrepare(Game $game): void
    {
        if ($game->isGameStarted() || $game->isBusy()) {
            return;
        }

        $templateRow = $this->getTemplateRowById((int) $this->msg['t_id']);
        $fromUserID = $this->getUserIdByTelegramChatId((int) $this->msg['f']);
        $recipientUserID = $this->getUserIdByTelegramChatId((int) $this->msg['r']);

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
        $this->setTableBusy($game, $fromUserID);
    }

    /**
     * @throws GuzzleException
     * @throws \JsonException
     */
    private function getUserIdByTelegramChatId(int $chatId): int
    {
        $response = $this->sendRequest('/api/bot/getUserIdByTelegramChatId', ['chat_id' => $chatId]);
        if (! $response['user_id']) {
            throw new \RuntimeException('can not get user id');
        }
        return (int) $response['user_id'];
    }

    /**
     * @throws GuzzleException
     * @throws \JsonException
     */
    private function getTemplateRowById(int $templateID): array
    {
        $response = $this->sendRequest('/api/bot/getGameSettingsById', ['template_id' => $templateID]);
        if (! $response['settings']) {
            throw new \RuntimeException('can not get settings');
        }
        return $response['settings'];
    }

    /**
     * @throws GuzzleException
     * @throws \JsonException
     */
    private function setTableBusy(Game $game, $fromUserID): void
    {
        $response = $this->sendRequest('/api/bot/setTableBusy', ['user_id' => $fromUserID]);
        if ($response['data']) {
            $game->setTableOccupationID($response['table_occupation_id']);
        }
    }

    private function sendToSaveGame(Game $game): bool
    {
        $request = $this->client->post($this->buildQuery('/api/socket/saveGame'), [
            'form_params' => $game->getState()
        ]);
        return $request->getStatusCode() === 200;
    }

    /**
     * @throws GuzzleException
     * @throws \JsonException
     */
    private function sendRequest(string $url, array $params = []): array
    {
        $request = $this->client->post($this->buildQuery($url), [
            'form_params' => $params
        ]);

        if ($request->getStatusCode() !== 200) {
            throw new \RuntimeException('request has an error ' . $request->getStatusCode());
        }

        $response = $request->getBody()->getContents();
        $decoded = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if (! is_array($decoded)) {
            throw new \RuntimeException('response cant decoded');
        }
        return $decoded;
    }

    private function buildQuery(string $uri): string
    {
        return self::HOST . $uri;
    }

    private function notFound(Game $game): void
    {
        return;
    }
}
