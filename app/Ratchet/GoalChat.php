<?php

namespace App\Ratchet;

use Exception;
use Illuminate\Support\Facades\Log;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class GoalChat implements MessageComponentInterface
{
    protected \SplObjectStorage $clients;
    protected GameMessageHandler $handler;
    protected MessageRegistry $messageRegistry;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->handler = new GameMessageHandler();
        $this->messageRegistry = MessageRegistry::getInstance();
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        try {
            $data = json_decode($msg, true, 512, JSON_THROW_ON_ERROR);

            if (empty($data) || empty($data['cmd'])) {
                throw new \RuntimeException('CMD is not defined');
            }

            $this->handler->handleMessage($data);

            $this->publishInfo($from, $data['cmd']);

        } catch (\Throwable $exception) {
            var_dump($exception->getMessage());
            var_dump($exception->getLine());
        }
    }

    /**
     * @throws \JsonException
     */
    protected function publishInfo(ConnectionInterface $from, string $cmd): void
    {
        $response = json_encode($this->messageRegistry->getData(), JSON_THROW_ON_ERROR);

        if ($cmd === 'state') {
            $from->send($response);
            return;
        }

        foreach ($this->clients as $client) {
            $client->send($response);
        }
    }

    /**
     * @param ConnectionInterface $conn
     * @return void
     */
    public function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);
    }

    /**
     * @param ConnectionInterface $conn
     * @param Exception $e
     * @return void
     */
    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        $conn->close();
    }
}
