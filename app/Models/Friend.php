<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Friend extends Model
{

    protected $table = 'friends';
    protected $fillable = ['user_id', 'friend_id'];
    public $timestamps = false;
    protected $primaryKey = ['user_id', 'friend_id'];
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function friend(){
        return $this->belongsTo(User::class, 'friend_id');
    }
    
    public function friends()
    {
        return $this->belongsToMany(
            User::class,
            'friends',
            'user_id',
            'friend_id'
        );
    }

    public function friendOf()
    {
        return $this->belongsToMany(
            User::class,
            'friends',
            'friend_id',
            'user_id'
        );
    }
}