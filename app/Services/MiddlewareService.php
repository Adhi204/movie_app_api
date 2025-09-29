<?php

namespace App\Services;

// use App\Http\Middleware\EnsureUserIsAdmin;
// use App\Http\Middleware\EnsureUserIsNotRestricted;
use App\Http\Middleware\RefreshAuthToken;
// use App\Http\Middleware\UpdateUserLastActivity;
use Illuminate\Foundation\Configuration\Middleware;

class MiddlewareService
{
    // Groups to which global middleware has to be added to
    const GLOBAL_GROUPS = [
        'web',
        'api',
    ];

    // Custom middleware groups
    // const CUSTOM_GROUPS = [
    //     'admin'
    // ];

    // List of global middleware
    const GLOBAL_MIDDLEWARE = [
        // UpdateUserLastActivity::class,
        RefreshAuthToken::class,
    ];

    // Middleware for each group
    const GROUP_MIDDLEWARE = [
        //global groups
        'api' => [
            'throttle:api',
        ],

        //custom groups
        // 'admin' => [
            // EnsureUserIsAdmin::class,
        //     EnsureUserIsNotRestricted::class,
        // ],
    ];

    public function __construct(protected Middleware $middleware) {}

    /**
     * Register middleware.
     *
     * @return void
     */
    public function register(): void
    {
        $this->globalMiddleware();
        // $this->customGroups();
    }

    /**
     * Append global middleware to all middleware groups.
     * 
     * @return void
     */
    protected function globalMiddleware(): void
    {
        foreach (self::GLOBAL_GROUPS as $group) {
            $this->middleware->appendToGroup($group, self::GLOBAL_MIDDLEWARE);

            if (isset(self::GROUP_MIDDLEWARE[$group])) {
                foreach (self::GROUP_MIDDLEWARE[$group] as $middleware) {
                    $this->middleware->appendToGroup($group, $middleware);
                }
            }
        }
    }

    /**
     * Register custom middleware groups.
     * 
     * @return void
     */
    // protected function customGroups(): void
    // {
    //     foreach (self::CUSTOM_GROUPS as $group) {
    //         $this->middleware->group($group, self::GROUP_MIDDLEWARE[$group]);
    //     }
    // }
}
