<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// 👇 ESTAS SON LAS LÍNEAS QUE FALTABAN 👇
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Configurar Paginación con Bootstrap 5
        Paginator::useBootstrapFive();
        
        // FORZAR ESPAÑOL EN FECHAS (Carbon)
        Carbon::setLocale('es');
        setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'esp');
    }
}