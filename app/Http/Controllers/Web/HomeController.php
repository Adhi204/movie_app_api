<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Library\Interfaces\Routable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller implements Routable
{
    /** Declare the routes for the controller */
    public static function routes(): void
    {
        Route::get('web', [self::class, 'welcome'])->name('web');
    }

    public function welcome()
    {
        return view('welcome');
    }
}
