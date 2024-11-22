<?php

namespace App\Services\Verification;

use Carbon\Carbon;
use App\Models\Verification;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Interfaces\Authentication\VerificationInterface;

class VerifyMobile implements VerificationInterface
{
    use ResponseTrait;

    private $auth;
    public function __construct()
    {
        $this->auth = auth()->user();
    }
    public function sendNotification(): JsonResponse
    {
        if(!$this->auth->hasVerifiedMobile())
        {
            $this->auth->sendMobileVerificationNotification();

            return $this->successResponse(message:'Verification code sent to your mobile.');
        }

        return $this->errorResponse(message:'Mobile number is already verified.', statusCode: 400);
    }

    public function verify($code): JsonResponse
    {

        $verification = $this->auth->verification()->first(['token' , 'expires_at']);


        if($this->auth->hasVerifiedMobile())
        {
            return $this->errorResponse(message:'Mobile number is already verified.', statusCode: 400);
        }

        if (!$verification) {
            return $this->errorResponse(message:'Verification record not found.', statusCode: 404);
        }

        if ($verification->token !== $code || now()->greaterThan($verification->expires_at)) {
             return $this->errorResponse(message:'Invalid or expired code.', statusCode: 400);
        }

        $this->auth->markMobileAsVerified();

        $this->auth->verification()->delete();

        return $this->successResponse(message:'Mobile number verified successfully.');
    }
}
