<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require_once('chat.php');

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Chat()
            )
        ),
        8888
    );

    $server->run();