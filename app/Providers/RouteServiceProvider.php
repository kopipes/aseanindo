<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {

            if (in_array(request()->getHost(), ['www.kontakami.com', 'kontakami.com', 'localhost', '127.0.0.1', 'proyek.test', '192.168.50.118', 'kontakami.loggy.my.id'])) {
                // Route::middleware('web')
                //     ->group(base_path('routes/web.php'));


                if (in_array(request()->getHost(), ['localhost', 'proyek.test', '192.168.50.118', 'kontakami.loggy.my.id'])) {
                    Route::middleware(['api', 'force-json', 'api-guest'])
                        ->prefix('v1')
                        ->group(base_path('routes/api/api.php'));
                }

                Route::middleware(['api', 'callnchat-api'])
                    ->prefix('api')
                    ->as('callnchat-api.')
                    ->group(base_path('routes/callnchat/callnchat_api.php'));

                Route::middleware('web')
                    ->group(base_path('routes/callnchat/callnchat.php'));

            } else {

                Route::middleware(['api', 'force-json', 'api-guest'])
                    ->prefix('v1')
                    ->group(base_path('routes/api/api.php'));
            }
        });
    }
}
