<?php

namespace App\Ratchet;

use Exception;
use JsonException;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class GoalChat implements MessageComponentInterface
{
    protected \SplObjectStorage $clients;
    private Game $game;
    private array $payload = [];

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->game = new Game();
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        try {
            $this->runAction($msg);
            $this->publishInfo($from);
            $this->checkForGameOver();
        } catch (Exception $exception) {
            custom_log($exception->getMessage() . ' in line ' . $exception->getLine(), true);
        }
    }

    /**
     * @throws JsonException
     */
    private function runAction(string $msg): void
    {
        $this->setPayload($msg);
        $callback = $this->getCallback();
        $this->$callback();
    }

    private function handle(): void
    {
        GameMessageHandler::handle($this->game, $this->payload);
    }

    private function createNewGame(): void
    {
        $this->game = new Game();
    }

    private function getCallback(): string
    {
        $cmd = $this->payload['cmd'];
        $callbacks = ['new_game' => 'createNewGame'];
        return $callbacks[$cmd] ?? 'handle';
    }

    /**
     * @throws JsonException
     */
    private function publishInfo(ConnectionInterface $from): void
    {
        $encoded = json_encode($this->game->getState(), JSON_THROW_ON_ERROR);
        if ($this->isGetStateCmd()) {
            $from->send($encoded);
            return;
        }
        foreach ($this->clients as $client) {
            $client->send($encoded);
        }
    }

    /**
     * @throws JsonException
     */
    private function setPayload(string $msg): void
    {
        $decoded = json_decode($msg, true, 512, JSON_THROW_ON_ERROR);
        if (! is_array($decoded)) {
            throw new \RuntimeException('message can not decoded');
        }
        $this->payload = $decoded;
    }

    private function isGetStateCmd(): bool
    {
        return $this->payload['cmd'] === 'state';
    }

    private function checkForGameOver(): void
    {
        if ($this->game->isGameOver()) {
            $this->game = new Game();
        }
    }

    public function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        $conn->close();
    }
}
