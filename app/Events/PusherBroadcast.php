<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PusherBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    public $tempId;

    public function __construct($message, $tempId)
    {
        $this->message = $message;
        $this->tempId = $tempId;
    }

    public function broadcastOn(): array
    {
        $ids = [$this->message->sender_id, $this->message->receiver_id];
        sort($ids);
        return [
            new PrivateChannel('chat.' . $ids[0] . '.' . $ids[1])
        ];
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'content' => $this->message->content,
            'created_at' => $this->message->created_at->toDateTimeString(),
            'sender_first_name' => $this->message->sender->first_name ?? '',
            'sender_last_name' => $this->message->sender->last_name ?? '',
            'temp_id' => $this->tempId
        ];
    }

    public function broadcastAs()
    {
        return 'chat';
    }

    public function broadcastQueue(){
        return 'default';
    }
}