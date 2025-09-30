<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\GenerateToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Library\Interfaces\Routable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Route;

class RegisterController extends Controller implements HasMiddleware, Routable
{
    /**
     * Define the middleware that should be applied to routes in this controller.
     *
     * @return array
     */
    public static function middleware(): array
    {
        return [
            //
        ];
    }

    /**
     * Define the routes for the LoginController.
     * This method should be called in the routes file to register the routes.
     * 
     * @return void
     * @see \App\Library\Interfaces\Routable
     */
    public static function routes(): void
    {
        Route::post('register', [self::class, 'register']);
    }

    /**
     * Handle a registration for the application.
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'username' => $request->safe()->username,
            'email' => $request->safe()->email,
            'password' => $request->safe()->password
        ]);

        $token = GenerateToken::run($user);

        return response()->json([
            'title' => 'Registration Successful',
            'message' => "You have been registered successfully",
            'user' => $user->toResource(),
            'token' => $token,
        ], 200);
    }
}
