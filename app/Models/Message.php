<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = ['sender_id', 'receiver_id', 'content', 'is_read', 'dialog_id', 'reply_to_id'];
    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:s\Z',
        'updated_at' => 'datetime:Y-m-d\TH:i:s\Z',
    ];

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function dialog() {
        return $this->belongsTo(Dialog::class);
    }

    public function images() {
        return $this->hasMany(MessageImage::class);
    }

    public function videos() {
        return $this->hasMany(MessageVideo::class);
    }
    public function video() {
        return $this->hasOne(MessageVideo::class);
    }

    public function replyTo() {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    public function replies() {
        return $this->hasMany(Message::class, 'reply_to_id');
    }
}