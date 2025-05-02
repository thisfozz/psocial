<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class PostImage extends Model
{
    protected $table = 'post_images';
    protected $fillable = ['post_id', 'user_id', 'image_path'];
    public $timestamps = true;

    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute() {
        return Storage::disk('s3')->temporaryUrl(
            $this->attributes['image_path'],
            now()->addMinutes(5)
        );
    }

    public function delete() {
        Storage::delete($this->image_path);
        parent::delete();
    }
}