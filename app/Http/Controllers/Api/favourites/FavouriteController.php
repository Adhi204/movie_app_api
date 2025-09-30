<?php

namespace App\Http\Controllers\Api\favourites;

use App\Http\Controllers\Controller;
use App\Http\Requests\favourites\CreateFavouriteRequest;
use App\Library\Interfaces\Routable;
use App\Models\Favourite;
use App\Models\Movie;
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
                Route::get('', [self::class, 'index']);
                Route::post('create', [self::class, 'addFavourite']);
                Route::post('{movie}/remove', [self::class, 'removeFavourite']);
            });
    }

    /**
     *  Get favourites list of the authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $favourites = Favourite::with(['movie', 'user'])
            ->where('user_id', $user->id)
            ->get();

        return response()->json([
            'favourite' => $favourites->toResourceCollection(),
        ]);
    }

    /**
     * add a movie to the authenticated user's favourites.
     */
    public function addFavourite(CreateFavouriteRequest $request)
    {
        $user = $request->user();

        $favourites = Favourite::create([
            'user_id' => $user->id,
            'movie_id' => $request->safe()->movie_id,
        ]);

        $favourites->movie()->increment('like_count');

        return response()->json([
            'title' => 'Favourite Created',
            'message' => 'Favourite created successfully',
            'favourite' => $favourites->load(['movie'])->toResource(),
        ]);
    }

    /**
     * * remove a movie from the authenticated user's favourites list.
     */
    public function removeFavourite(Request $request, Movie $movie)
    {
        $user = $request->user();
        $favourite = Favourite::where('user_id', $user->id)
            ->where('movie_id', $movie->id)
            ->delete();

        return response()->json([
            'title' => 'Favourite Removed',
            'message' => 'Favourite Removed successfully',
        ]);
    }
}
