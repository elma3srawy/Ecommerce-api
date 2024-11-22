<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Str;

trait GenerateToken
{
    private $className;

    protected function generate($className)
    {
        $this->className = $className;
        return $this;
    }

    protected function code(): string
    {
        $code = rand(100000, 999999);

        $this->updateOrInsertToken($code);
        return $code;
    }
    protected function token(): string
    {
        $token = Str::random(64);
        $this->updateOrInsertToken($token);
        return $token;
    }
    private function updateOrInsertToken($token)
    {
        $minutes = (int)env('TOKEN_EXPIRATION_MINUTES', 15);
        $this->className::updateOrInsert(
            [
                'tokenable_id' => $this->id,
                'tokenable_type' => static::class,
            ],
            [
                'token' => $token,
                'expires_at' => Carbon::now()->addMinutes($minutes),
            ]
        );

    }
}
