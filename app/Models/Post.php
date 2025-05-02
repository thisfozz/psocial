<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PostLike;

class Post extends Model
{
    protected $table = 'posts';
    protected $fillable = ['user_id', 'wall_id', 'content'];
    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function author() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function wallOwner() {
        return $this->belongsTo(User::class, 'wall_id');
    }

    public function likes() {
        return $this->hasMany(PostLike::class);
    }

    public function likesCount(){
        return $this->likes()->count();
    }
    public function hasLikeFrom(User $user){
        return $this->likes()->where('user_id', $user->id)->exists();
    }
    public function images() {
        return $this->hasMany(PostImage::class);
    }
}