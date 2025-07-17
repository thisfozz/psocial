<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEdited implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageId;
    public $senderId;
    public $receiverId;
    public $content;
    public $createdAt;
    public $updatedAt;

    public function __construct($messageId, $senderId, $receiverId, $content, $createdAt, $updatedAt)
    {
        $this->messageId = $messageId;
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
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
            'content' => $this->content,
        ];
    }

    public function broadcastAs()
    {
        return 'message.edited';
    }
}
