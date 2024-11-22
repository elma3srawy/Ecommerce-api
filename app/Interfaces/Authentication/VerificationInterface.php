<?php


namespace App\Interfaces\Authentication;

use Illuminate\Http\JsonResponse;

interface VerificationInterface
{
    public function sendNotification():JsonResponse;

    public function verify(string $token):JsonResponse;
}
