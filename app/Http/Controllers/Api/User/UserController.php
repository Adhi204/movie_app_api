<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateUserProfileRequest;
use App\Library\Interfaces\Routable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
                Route::post('update', [self::class, 'updateProfile']);
                Route::post('update_password', [self::class, 'updatePassword']);
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

    public function updateProfile(UpdateUserProfileRequest $request)
    {
        $user = $request->user();

        $request->safe()->username && $user->username = $request->safe()->username;
        $request->safe()->email && $user->email = $request->safe()->email;

        if ($user->isDirty()) {
            $user->save();
        }

        return response()->json([
            'title' => 'Profile Updated',
            'message' => "User profile updated successfully",
            'user' => $user->toResource(),
        ], 200);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();

        $current_password = $request->safe()->input('current_password');

        if (!Hash::check($current_password, $user->password)) {
            return response()->json([
                'title' => 'Incorrect Password',
                'message' => 'Old password given is incorrect',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $user->update(['password' => $request->safe()->password]);

            $currentAccessToken = $user->currentAccessToken();
            $user->tokens()->where('id', '!=', $currentAccessToken->id)->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json([
            'title' => 'Password Updated',
            'message' => 'Password updated successfully'
        ], 200);
    }
}
