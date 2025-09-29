<?php

namespace App\Http\Controllers\Api\favourites;

use App\Http\Controllers\Controller;
use App\Library\Interfaces\Routable;
use App\Models\Favourite;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Route;

class FavouriteController extends Controller implements HasMiddleware, Routable
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
        Route::prefix('favourites')
            ->controller(self::class)
            ->group(function () {
                Route::post('', [self::class, 'index']);
            });
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $favourite = Favourite::where('user_id', $user->id)
            ->with('user')
            ->get();

        return response()->json([
            'favourite' => $favourite->toResourceCollection(),
        ]);
    }
}
