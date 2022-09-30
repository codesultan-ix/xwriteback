<?php

namespace App\Actions;

use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateOTP
{
    use asAction;

    /**
     * @throws \Exception
     */
    public function handle($user, $type = 'code')
    {
        $otp = $user->otp()->create([
            'code' => $type === 'code' ? random_int(1000, 9999) : Str::random(32),
            'expires_at' => now()->addMinutes(5),
            'type' => 'email',
        ]);

        return $otp->code;
    }
}
