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
        'is_deleted'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

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
    

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'is_deleted' => 'boolean',
            'deleted_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
