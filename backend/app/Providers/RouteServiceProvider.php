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
            // Konfigurasi Rate Limiting untuk API
            RateLimiter::for('api', function (Request $request) {
                return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
            });

            // Memuat rute-rute aplikasi
            $this->routes(function () {
                // Memuat rute API dari routes/api.php
                Route::middleware('api') // Menggunakan middleware group 'api'
                    ->prefix('api')      // Memberi prefix '/api' pada semua rute di file ini
                    ->group(base_path('routes/api.php')); // Mengarahkan ke file api.php

                // Memuat rute web dari routes/web.php
                Route::middleware('web') // Menggunakan middleware group 'web'
                    ->group(base_path('routes/web.php')); // Mengarahkan ke file web.php
            });
        }
    }
    