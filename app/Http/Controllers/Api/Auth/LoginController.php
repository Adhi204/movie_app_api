<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\GenerateToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Library\Interfaces\Routable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller implements HasMiddleware, Routable
{
    /**
     * Define the middleware that should be applied to routes in this controller.
     *
     * @return array
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum', except: ['login', 'register'])
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
        Route::post('login', [self::class, 'login']);
        Route::get('logout', [self::class, 'logout']);
    }

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

    /** 
     * * Handle a login request to the application.
     * */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['title' => 'Invalid Credentials', 'message' => 'Username or Password is incorrect'], 401);
        }

        $user = Auth::user();

        $token = GenerateToken::run($user);

        return response()->json([
            'token' => $token,
            'user' => $user->toResource()
        ]);
    }

    /**
     * Handle a logout request to the application.
     * */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json(['title' => 'Logout Successful', 'message' => 'You have been logged out.']);
    }
}
