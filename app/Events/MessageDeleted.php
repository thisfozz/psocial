<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
class MessageDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageId;
    public $senderId;
    public $receiverId;

    public function __construct($messageId, $senderId, $receiverId)
    {
        $this->messageId = $messageId;
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
    }

    public function broadcastOn()
    {
        $ids = [$this->senderId, $this->receiverId];
        sort($ids);
        return [
            new PrivateChannel('chat.' . $ids[0] . '.' . $ids[1])
        ];
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->messageId,
            'sender_id' => $this->senderId,
            'receiver_id' => $this->receiverId,
        ];
    }

    public function broadcastAs()
    {
        return 'message.deleted';
    }
}
