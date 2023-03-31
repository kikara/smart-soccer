<?php

namespace App\Ratchet;

use Exception;
use Illuminate\Support\Facades\Log;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class GoalChat implements MessageComponentInterface
{
    protected \SplObjectStorage $clients;
    protected Game $game;
    protected GameMessageHandler $handler;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->game = new Game();
        $this->handler = new GameMessageHandler();
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        try {
            $payload = $this->runAction($msg);
//            var_dump($payload);
            $this->publishInfo($from, $payload);
            $this->checkForGameOver();
        } catch (Exception $exception) {
//            Log::log('DEBUG', $exception->getMessage());
//            Log::log('DEBUG', var_export($msg, true));
            var_dump($exception->getMessage());
            var_dump($exception->getLine());

        }
    }

    private function runAction(string $msg): array
    {
        $data = $this->getPayload($msg);

        if (empty($data) || empty($data['cmd'])) {
            throw new \RuntimeException('CMD is not defined');
        }

        $this->handler->handleMessage($this->game, $data);

        return $data;
    }

    private function publishInfo(ConnectionInterface $from, array $payload): void
    {
        $encoded = json_encode($this->game->getState());
        if ($payload['cmd'] === 'state') {
            $from->send($encoded);
            return;
        }
        foreach ($this->clients as $client) {
            $client->send($encoded);
        }
    }

    private function getPayload(string $msg): array
    {
        try {
            $decoded = json_decode($msg, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $exception) {
            $decoded = [];
        }
        return $decoded;
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
