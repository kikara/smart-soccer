<?php

require dirname(__DIR__) . '/smart-soccer/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Ratchet\GoalChat;


$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new GoalChat()
        )
    ),
    8080
);

$server->run();
