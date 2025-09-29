<?php

namespace App\Http\Controllers\Api\movies;

use App\Http\Controllers\Controller;
use App\Http\Requests\movies\CreateMovieRequest;
use App\Http\Requests\movies\UpdateMovieRequest;
use App\Library\Interfaces\Routable;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Route;

class MoviesController extends Controller implements HasMiddleware, Routable
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
        Route::post('create', [self::class, 'create']);
        Route::post('{movie}/update', [self::class, 'update']);
    }

    public function create(CreateMovieRequest $request)
    {
        $user = $request->user();

        $movie = Movie::make([
            'user_id' => $user->id,
            'title' => $request->safe()->title,
            'year' => $request->safe()->year,
            'description' => $request->safe()->description,
            'poster' => $request->safe()->poster,
            'like_count' => 0,
        ]);

        if ($request->hasFile('poster')) {
            $movie->savePoster($request->file('poster'));
        }

        $movie->save();

        return response()->json([
            'title' => 'Movie created',
            'message' => "Movie created successfully",
            'movie' => $movie->toResource(),
        ], 200);
    }

    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        $movie->user_id = $request->user()->id;

        $request->safe()->title && $movie->title = $request->safe()->title;
        $request->safe()->year && $movie->year = $request->safe()->year;
        $request->safe()->description && $movie->description = $request->safe()->description;

        $request->safe()->delete_poster && $movie->deletePoster();

        if ($request->hasFile('poster')) {
            $movie->saveImage($request->file('poster'));
        }
        if ($movie->isDirty()) {
            $movie->update();
        }
        return response()->json([
            'title' => 'Movie updated',
            'message' => "Movie updated successfully",
            'movie' => $movie->toResource(),
        ]);
    }
}
