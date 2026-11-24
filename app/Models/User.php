<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\VerifyEmail;
use App\Notifications\PasswordResetNotification;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guard = "web";
    protected $table = "users";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'address',
        'email_verified_at',
        'password',
        'avatar',
        'verified',
        'usertype',
        'remember_token',
        'created_at',
        'updated_at',
        'last_active_at',
        'deleted_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'deleted_at' => 'datetime',
            'last_active_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted()
    {
        static::deleting(function ($user) {
            if ($user->twoFactorCode) {
                $user->twoFactorCode->delete();
            }
        });
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail($this));
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token, $this->email));
    }

    public function routeNotificationForTwilio($notification = null)
    {
        return $this->phone;
    }

    public function twoFactorCode()
    {
        return $this->hasOne(TwoFactorCode::class);
    }

}
