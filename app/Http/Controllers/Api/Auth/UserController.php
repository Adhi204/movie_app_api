<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Library\Interfaces\Routable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Route;

class UserController extends Controller implements HasMiddleware, Routable
{
    /**
     * Define the middleware that should be applied to routes in this controller.
     *
     * @return array
     */
    public static function middleware(): array
    {
        return [
            new Middleware(
                'auth:sanctum'
            )
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
        Route::prefix('users')
            ->controller(self::class)
            ->group(function () {
                Route::get('', [self::class, 'index']);
            });
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $profile = User::where('id', $user->id)
            ->get();

        return response()->json([
            'user' => $profile->toResourceCollection(),
        ]);
    }
}
