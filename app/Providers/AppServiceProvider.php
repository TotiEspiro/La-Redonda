<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * 1. Soporte para Túneles de Desarrollo (ngrok)
         */
        if (str_contains(request()->getHost(), 'ngrok-free.dev')) {
            URL::forceScheme('https');
        }

        /**
         * 2. Soporte para Producción y Entornos Remotos
         * Usamos config('app.env') en lugar de env() directamente para evitar 
         * advertencias de linting y asegurar que funcione con config:cache.
         */
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        /**
         * 3. Manejo de cabeceras de Proxy
         * Ayuda a Laravel a entender que la petición original vino por HTTPS
         * a través de un balanceador de carga o proxy inverso.
         */
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            request()->server->set('HTTPS', 'on');
        }
    }
}