<?php

namespace App\Http\Controllers\Webscokets\SocketHandler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use BeyondCode\LaravelWebSockets\QueryParameters;
use BeyondCode\LaravelWebSockets\Apps\App;
use BeyondCode\LaravelWebSockets\WebSockets\Exceptions\UnknownAppKey;
use BeyondCode\LaravelWebSockets\WebSockets\Exceptions\WebSocketException;
use BeyondCode\LaravelWebSockets\WebSockets\Exceptions\ConnectionsOverCapacity;

abstract class BaseSocketHandler implements MessageComponentInterface
{

    public function onOpen(ConnectionInterface $connection)
    {
        // TODO: Implement onOpen() method.
        $this
            ->verifyAppKey($connection)
            ->generateSocketId($connection)
            ->establishConnection($connection);
        // print_r($connection->httpRequest);
        // dump("open");
    }
    
    public function onClose(ConnectionInterface $connection)
    {
        // TODO: Implement onClose() method.
        
    }

    public function onError(ConnectionInterface $connection, \Exception $exception)
    {
        if ($exception instanceof WebSocketException) {
            $connection->send(json_encode(
                $exception->getPayload()
            ));
        }
    }
    protected function verifyAppKey(ConnectionInterface $connection)
    {
        $appKey = QueryParameters::create($connection->httpRequest)->get('appKey');

        if (! $app = App::findByKey($appKey)) {
            throw new UnknownAppKey($appKey);
        }

        $connection->app = $app;

        return $this;
    }

    protected function generateSocketId(ConnectionInterface $connection)
    {
        $socketId = sprintf('%d.%d', random_int(1, 1000000000), random_int(1, 1000000000));

        $connection->socketId = $socketId;

        return $this;
    }

    protected function establishConnection(ConnectionInterface $connection)
    {
        $connection->send(json_encode([
            'event' => 'pusher:connection_established',
            'data' => json_encode([
                'socket_id' => $connection->socketId,
                'activity_timeout' => 30,
            ]),
        ]));


        return $this;
    }

}