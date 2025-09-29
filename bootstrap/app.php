<?php

use App\Actions\Scheduler;
use App\Services\MiddlewareService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function ($router) {
            Route::middleware('api')
                ->group(base_path('routes/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        new MiddlewareService($middleware)->register();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withProviders([
        RouteServiceProvider::class,
    ])
    ->withCommands([
        __DIR__ . '/../app/Library/Console',
    ])
    ->withSchedule(function (Schedule $schedule) {
        Scheduler::run($schedule);
    })
    ->create();
