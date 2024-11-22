<?php

namespace App\Models;

use App\Traits\GenerateToken;
use App\Events\SmsNotification;
use App\Traits\MustVerifyMobile;
use App\Jobs\SendSmsNotification;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Interfaces\Authentication\MustVerifyMobile as VerifyMobileInterface;

class Staff extends Authenticatable implements VerifyMobileInterface
{
    /** @use HasFactory<\Database\Factories\StaffFactory> */
    use  HasApiTokens, HasFactory, Notifiable, MustVerifyMobile , GenerateToken;

    protected $table = 'staffs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
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

    public function verification(): MorphOne
    {
        return $this->morphOne(Verification::class, 'tokenable');
    }

    public function generateToken()
    {
        return $this->generate(Verification::class)->code();
    }

    public function sendMobileSmsCodeToResetPassword()
    {
        event(new SmsNotification($this->generateToken(), $this->phone));
    }
}
