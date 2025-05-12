<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use App\Models\City;

class User extends Authenticatable implements AuthenticatableContract, CanResetPassword
{
    use HasFactory, CanResetPasswordTrait, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'username',
        'email',
        'phone_number',
        'password',
        'first_name',
        'last_name',
        'avatar_path',
        'family_status_id',
        'cities_id',
        'date_of_birth',
        'about_me',
        'status',
        'is_deleted',
        'last_seen'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'is_deleted' => 'boolean',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_seen' => 'datetime',
    ];

    const ONLINE_MINUTES = 15;

    public function getAuthIdentifier(): int{
        return $this->id;
    }

    public function getAuthPassword(): string{
        return $this->password;
    }

    public function hasVerifiedEmail(): bool{
        return !is_null($this->email_verified_at);
    }

    public function markEmailAsVerified(): bool{
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function sendEmailVerificationNotification(): void{
        $this->notify(new VerifyEmail);
    }

    public function getEmailForVerification(): string{
        return $this->email;
    }

    public function getEmailVerificationNotification(): Notification{
        return new VerifyEmail;
    }
    
    public function city(){
        return $this->belongsTo(City::class, 'cities_id', 'id');
    }

    public function familyStatus(){
        return $this->belongsTo(FamilyStatus::class, 'family_status_id', 'id');
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

    public function receivedFriendRequests(){
        return $this->hasMany(FriendRequest::class, 'to_user_id');
    }

    public function sentFriendRequests(){
        return $this->hasMany(FriendRequest::class, 'from_user_id');
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function postsLikes(){
        return $this->belongsToMany(
            Post::class,
            'post_likes',
            'user_id',
            'post_id'
        );
    }

    public function messages(){
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function lastSeen()
    {
        return $this->last_seen && $this->last_seen > now()->subMinutes(self::ONLINE_MINUTES);
    }
}