<?php

namespace App\Providers;

use App\Enums\ItemType;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        Route::bind('item', function (string $value, RoutingRoute $route) {
            if (!$route->parameter('item_type')) {
                return $value;
            }

            $item_type = ItemType::tryFrom($route->parameter('item_type'));

            if (!$item_type) {
                abort(404, 'Item not found');
            }

            $item = $item_type->wherePrimaryKey($value)->first();

            if (!$item) {
                abort(404, 'Item not found');
            }

            return $item;
        });

        $this->routes(function () {
            Route::middleware(['api', 'throttle:api'])
                ->prefix('api')
                ->name('api.')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
