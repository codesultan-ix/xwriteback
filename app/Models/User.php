<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Actions\GenerateOTP;
use App\Models\Traits\HasUuid;
use App\Notifications\PasswordResetWithOTPNotification;
use App\Notifications\VerifyEmail;
use App\Notifications\VerifyEmailWithOTPNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MatanYadaev\EloquentSpatial\Objects\Point;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'location'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'location' => Point::class
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function sendPasswordResetNotification($token)
    {
        // $this->notify(new VerifyEmail);
    }

    public function otp(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(OTP::class, 'entity');
    }

    public function sendPasswordResetOTP()
    {
        $otp = GenerateOTP::run($this);

        $this->notify(new PasswordResetWithOTPNotification($this, $otp));
    }

    public function providers()
    {
        return $this->hasMany(Provider::class,'user_id','id');
    }


}
