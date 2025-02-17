<?php

use Ratchet\Server\IoServer;
// use Chat;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require dirname(__DIR__) . '/../vendor/autoload.php';
require __DIR__ . '/../classes/Models/webSocketChat.php';
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);
$server->run();
