<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $from_type;
    public $from_id;
    public $to_type;
    public $to_id;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->from_type = $message->from_user_type;
        $this->from_id = $message->from_user_id;
        $this->to_type = $message->to_user_type;
        $this->to_id = $message->to_user_id;
    }

    public function broadcastOn()
    {
        // using dynamic channel name for private messaging
        return new PrivateChannel("chat.{$this->from_type}.{$this->from_id}.{$this->to_type}.{$this->to_id}");
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message->message,
            'from' => [
                'id' => $this->from_id,
                'type' => $this->from_type,
                'name' => $this->message->from_user->name,
            ],
            'to' => [
                'id' => $this->to_id,
                'type' => $this->to_type,
            ],
            'timestamp' => $this->message->created_at->toDateTimeString(),
        ];
    }
}
