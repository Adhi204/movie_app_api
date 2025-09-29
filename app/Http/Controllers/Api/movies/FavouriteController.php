<?php

namespace App\Http\Controllers\Api\movies;

use App\Http\Controllers\Controller;
use App\Http\Requests\movies\CreateFavouriteRequest;
use App\Http\Resources\FavouriteResource;
use App\Library\Interfaces\Routable;
use App\Models\Favourite;
use App\Models\User;
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
        Route::post('index', [self::class, 'index']);
        Route::post('create', [self::class, 'create']);
    }

    public function index(Request $request)
    {
        $searchFilters = $request->only('id', 'title', 'year');

        $favourites  = Favourite::query()
            ->applyFilters($searchFilters)
            ->get();

        return FavouriteResource::Collection($favourites);
    }

    public function create(CreateFavouriteRequest $request)
    {
        $user = $request->user();

        $favourites = Favourite::create([
            'user_id' => $user->id,
            'title' => $request->safe()->title,
            'year' => $request->safe()->year,
            'description' => $request->safe()->description,
            'poster' => $request->safe()->poster,
            'like_count' => 0,
        ]);

        return response()->json([
            'title' => 'Movie created',
            'message' => "Movie created successfully",
            'location' => $favourites->toResource(),
        ], 200);
    }
}
