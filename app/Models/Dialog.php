<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dialog extends Model
{
    protected $table = 'dialogs';
    protected $fillable = ['user1_id', 'user2_id'];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }
    public function companion()
    {
        if($this->user1_id === auth()->user()->id) {
            return $this->user2;
        } else {
            return $this->user1;
        }
    }
}