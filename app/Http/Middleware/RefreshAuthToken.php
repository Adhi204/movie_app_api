<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefreshAuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($token = $user?->currentAccessToken()) {
            //Refresh time based on current token expiry time
            $refreshTokenAt = $token->expires_at->subMinutes(config('sanctum.refresh_before'));

            if ($refreshTokenAt->isPast()) {
                //Set new expiry time
                $newExpiresAt = now()->addMinutes(config('sanctum.expiration'));
                $token->update(['expires_at' => $newExpiresAt]);
            }
        }

        return $next($request);
    }
}
