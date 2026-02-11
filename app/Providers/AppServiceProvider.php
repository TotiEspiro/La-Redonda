<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // FORZAR HTTPS si estamos en producción o usando ngrok
        if (config('app.env') !== 'local' || str_contains(request()->getHost(), 'ngrok-free.dev')) {
            URL::forceScheme('https');
        }
    }
}