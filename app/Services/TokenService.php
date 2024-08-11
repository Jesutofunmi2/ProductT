<?php

namespace App\Services;

use App\Models\User;

class TokenService
{
    public function createTokenUser(User $user, string $device_name, string $ip = Null, string $user_agent = Null): string
    {
        $token = $user->createToken($device_name)->plainTextToken;

        return $token;
    }

    public function revokeCurrentToken(User $user)
    {
        return $user->tokens()->where('id', optional($user->currentAccessToken())->id)->delete();
    }
}
