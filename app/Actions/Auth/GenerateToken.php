<?php

namespace App\Actions\Auth;

use App\Library\Classes\Action;
use App\Models\User;
use App\Services\RequestService;

class GenerateToken extends Action
{
    // Execute the action
    public function __invoke(User $user)
    {
        // Generate a device name from the request to use as the token name
        $deviceName = new RequestService(request())->generateDeviceName();

        // Create new authentication token for the user
        $token = $user->createToken(
            $deviceName,
            ['*'],
            now()->addMinutes(config('sanctum.expiration'))
        )->plainTextToken;

        return $token;
    }
}
