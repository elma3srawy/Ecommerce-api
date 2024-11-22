<?php

namespace App\Interfaces\Authentication;


interface MustVerifyMobile
{

    /**
     * Determine if the user has verified their mobile address.
     *
     * @return bool
     */
    public function hasVerifiedMobile();

    /**
     * Mark the given user's mobile as verified.
     *
     * @return bool
     */
    public function markMobileAsVerified();

    /**
     * Send the mobile verification notification.
     *
     * @return void
     */
    public function sendMobileVerificationNotification();

    
    public function getMobileForVerification();
}
