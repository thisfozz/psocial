<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class MessageImage extends Model
{
    protected $table = 'message_images';
    protected $fillable = ['message_id', 'user_id', 'image_path'];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function getImageUrlAttribute() {
        return Storage::disk('s3')->temporaryUrl(
            $this->attributes['image_path'],
            now()->addMinutes(5)
        );
    }
}
