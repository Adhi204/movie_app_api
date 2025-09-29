<?php

namespace App\Http\Controllers\movies;

use App\Http\Controllers\Controller;
use App\Http\Resources\FavouriteResource;
use App\Library\Interfaces\Routable;
use App\Models\Favourite;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Collection;
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
            new Middleware('auth:sanctum', except: ['login'])
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
        Route::post('index', [self::class, 'index']);
    }

    public function index(Request $request)
    {
        $searchFilters = $request->only('id', 'title', 'year');

        $favourites  = Favourite::query()
            ->applyFilters($searchFilters)
            ->get();

        return FavouriteResource::Collection($favourites);
    }
}
