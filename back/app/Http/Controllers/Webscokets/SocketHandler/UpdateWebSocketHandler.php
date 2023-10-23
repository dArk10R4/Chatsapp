<?php

namespace App\Http\Controllers\Webscokets\SocketHandler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use App\Events\MessageEvent;
// use Illuminate\Mail\Events\MessageSent;
use App\Models\Message;
class UpdateWebSocketHandler extends BaseSocketHandler
{
    
    public function onMessage(ConnectionInterface $connection, MessageInterface $msg)
    {
        $payload = collect(json_decode($msg->getPayload()));
        // dump($payload);
        $message = Message::create([
            'content'=>$payload['content'],
            'from'=> $payload['from'],
            'to' => $payload['to'],
        ]);
        broadcast(new MessageEvent($message))->via('pusher');
        // MessageEvent::dispatch();
        // $connection->send(json_encode(['id'=>31]));
        // TODO: Implement onMessage() method.
    }
}
