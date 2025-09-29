<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider

{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    /**
     * Find all controller classes inside the relative path recursively
     * Register all routes using the routes method inside those controllers
     * 
     * @param string $relativePath path relative to app/Http/Controllers
     */
    public static function registerRoutes(string $relativePath): void
    {
        $pathToControllers = app_path('Http/Controllers/' . $relativePath);

        $rdi = new \RecursiveDirectoryIterator($pathToControllers);
        $iterator = new \RecursiveIteratorIterator($rdi);
        $controllerFiles = new \RegexIterator($iterator, '/^.+\.php$/i');

        foreach ($controllerFiles as $controllerFile) {
            $controllerClassName = str($controllerFile->getPathname())
                ->before('.php')
                ->replace(app_path(), '\App')
                ->replace('/', '\\')
                ->toString();

            $controllerClassName::routes();
        }
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(600)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('password_reset', function (Request $request) {
            return Limit::perMinute(2)->by($request->ip());
        });
    }
}
