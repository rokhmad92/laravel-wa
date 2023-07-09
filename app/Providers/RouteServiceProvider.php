<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
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
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // rate limit untuk resend otp
        $this->rateLimitOTP();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    protected function rateLimitOTP()
    {
        RateLimiter::for('otp', function (Request $request) {
            $key = 'otp.'.$request->ip();
            $max = 2;
            $decay = 60; // detik = 1 menit/60 detik
            if(RateLimiter::tooManyAttempts($key, $max)){
                return back()->with('error', 'Terlalu Banyak Request');
            } else {
                RateLimiter::hit($key, $decay);
            }
        });
    }
}
