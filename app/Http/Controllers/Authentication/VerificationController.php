<?php

namespace App\Http\Controllers\Authentication;

use App\Interfaces\Authentication\VerificationInterface;
use App\Http\Controllers\Controller;

class VerificationController extends Controller
{

    public function __construct(protected VerificationInterface $verification){
        //
    }
    public function sendNotification()
    {
        return $this->verification->sendNotification();
    }

    public function verify($token)
    {
        return $this->verification->verify($token);
    }

}
