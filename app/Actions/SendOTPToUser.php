<?php

namespace App\Actions;

use App\Notifications\VerifyEmailWithOTPNotification;
use Lorisleiva\Actions\Concerns\AsAction;

class SendOTPToUser
{
    use asAction;

    public function handle($user): void
    {
        $otp = GenerateOTP::run($user);

        $user->notify(new VerifyEmailWithOTPNotification($user, $otp));
    }
}
