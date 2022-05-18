<?php

namespace App\Models\Auth\Traits;

use App\Notifications\Frontend\Auth\UserServiceRequestSuccess;

/**
 * Class SendUserPasswordReset.
 */
trait SendUserServiceRequestSuccess
{
    /**
     * Send the password reset notification.
     *
     * @param string $token
     */
    public function sendUserServiceRequestSuccessNotification($token)
    {
        $this->notify(new UserServiceRequestSuccess($token));
    }
}
