<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Queue\SerializesModels;
class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, InteractsWithBroadcasting;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    // private  User $user;
    private  Message $message;
    public function __construct(Message $message)
    {
        $this->broadcastVia('pusher');
        $this->message = $message;
        // dump($message->recipient->name);
        // $this->user = $user;
        // $this->message = $message;
        
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chat.'.$this->message->to);
    }
    // public function broadcastAs() 
    // {
    //     return 'message.sent';
    // }

    public function broadcastWith(): array
    {
        return [
            // 'message' => $this->message,
            'sentTo' => $this->message->to,
            "body" => [
                'from' => $this->message->from,
                'to' => $this->message->to,
                'content' => $this->message->content,
                'name' => $this->message->owner->name,
            ]
        ];
    }
    public function broadcastAs()
    {
        return "new.message";
    }
}
