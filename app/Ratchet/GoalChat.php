<?php

namespace App\Ratchet;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class GoalChat implements MessageComponentInterface
{
    protected $clients;
    private Game $game;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->game = Game::newGame();
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        GameMessageHandler::handle($this->game, $msg);
        $encoded = json_encode($this->game->getState());
        foreach ($this->clients as $client) {
            $client->send($encoded);
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}