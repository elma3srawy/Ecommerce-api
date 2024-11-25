<?php

namespace App\Services\Verification;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Verification;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Authentication\VerificationInterface;

class VerifyEmail implements VerificationInterface
{
    use ResponseTrait;

    private $auth;
    public function __construct()
    {
        $this->auth = Auth::user();
    }
    public function sendNotification(): JsonResponse
    {

        if($this->auth instanceof Admin &&  !$this->auth->hasVerifiedEmail())
        {
            $this->auth->sendEmailVerificationWithToken();

            return $this->successResponse(message:'Verification email sent successfully.');
        }

        return $this->errorResponse(message:'Email is already verified.', statusCode: 400);
    }
    public function verify($token): JsonResponse
    {
        if ($this->auth instanceof Admin){
            $verification = $this->auth->verification()->first(['token' , 'expires_at']);

            if($this->auth->hasVerifiedEmail())
            {
                return $this->errorResponse(message:'Email address is already verified.', statusCode: 400);
            }

            if (!$verification) {
                return $this->errorResponse(message:'Verification record not found.', statusCode: 404);
            }

            if ($verification->token !== $token || now()->greaterThan($verification->expires_at)) {
                return $this->errorResponse(message:'Invalid or expired token.', statusCode: 400);
            }

            $this->auth->markEmailAsVerified();

            $this->auth->verification()->delete();

            return $this->successResponse(message:'Email verified successfully.');
        }
        return $this->errorResponse(message:'You are not authorized to perform this action.');

    }
}
