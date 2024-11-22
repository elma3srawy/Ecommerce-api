<?php

namespace App\Traits;

use App\Events\SmsNotification;



trait MustVerifyMobile
{
    /**
     * Determine if the user has verified phone number.
     *email
     * @return bool
     */
    public function hasVerifiedMobile()
    {
        return ! is_null($this->mobile_verified_at);
    }

    /**
     * Mark the given user's phone as verified.
     *
     * @return bool
     */
    public function markMobileAsVerified()
    {
        return $this->forceFill([
            'mobile_verified_at' => $this->freshTimestamp(),
        ])->save();
    }


    public function sendMobileVerificationNotification()
    {
        event(new SmsNotification($this->generateToken(), $this->phone));
    }

    /**
     * Get the phone number that should be used for verification.
     *
     * @return string
     */
    public function getMobileForVerification()
    {
        return $this->phone;
    }
}

