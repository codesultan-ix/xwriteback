<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateAPIToken
{
    use AsAction;

    public function handle($user, array $data, array $abilities): string
    {
        $expires_in_days = (isset($data['remember_me']) && $data['remember_me']) ? 30 : 3;

        return $this->generateTokenForUser($user, $data, $expires_in_days, $abilities);
    }

    protected function generateTokenForUser($user, array $data, int $expires_in_days, array $abilities): string
    {
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        Log::info("Generated User API token for {$user->id}");

        $user->tokens()->delete();

        return $user->createToken($data['device_name'] ?? 'webapp', $abilities, now()->addDays($expires_in_days))
            ->plainTextToken;
    }
}
