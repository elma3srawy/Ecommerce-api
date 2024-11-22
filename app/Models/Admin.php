<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use App\Traits\GenerateToken;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\StaffFactory> */
    use  HasApiTokens, HasFactory, Notifiable , GenerateToken;

     protected $table = 'admins';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'updated_at',
        'created_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function sendEmailVerificationWithToken()
    {
        $this->notify(new VerifyEmail($this->generateToken()));
    }
    public function sendEmailToResetPasswordWithToken()
    {
        $this->notify(new ResetPasswordNotification($this->generateToken()));
    }

    public function verification(): MorphOne
    {
        return $this->morphOne(Verification::class, 'tokenable');
    }


    public function generateToken()
    {
        return $this->generate(Verification::class)->token();
    }


}
