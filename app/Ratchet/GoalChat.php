<?php

namespace App\Ratchet;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class GoalChat implements MessageComponentInterface
{
    protected \SplObjectStorage $clients;
    private Game $game;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->game = Game::newGame();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $decoded = json_decode($msg, true);
        $callback = $this->getCallback($decoded['cmd']);
        $this->$callback($decoded);
        $encoded = json_encode($this->game->getState());
        foreach ($this->clients as $client) {
            $client->send($encoded);
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    private function handle($decoded)
    {
        GameMessageHandler::handle($this->game, $decoded);
    }

    private function createNewGame($decoded)
    {
        $this->game = Game::newGame();
    }

    private function getCallback($cmd): string
    {
        $callbacks = ['new_game' => 'createNewGame'];
        return $callbacks[$cmd] ?? 'handle';
    }
}
