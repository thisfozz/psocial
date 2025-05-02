<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\User;

class PostLike extends Model
{ 
    protected $table = 'post_likes';
    public $incrementing = false;
    protected $fillable = ['post_id', 'user_id'];
    protected $primaryKey = ['post_id', 'user_id'];
    public $timestamps = false;

    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}