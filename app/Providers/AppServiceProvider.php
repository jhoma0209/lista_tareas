<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\TareaRepository;
use App\Services\TareaService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registre cualquier servicio de aplicación.
     */
    public function register(): void
    {
        $this->app->bind(TareaRepository::class, function ($app) {
            return new TareaRepository();
        });
        
        $this->app->bind(TareaService::class, function ($app) {
            return new TareaService($app->make(TareaRepository::class));
        });
    }

    /**
     * Arranque cualquier servicio de aplicación.
     */
    public function boot(): void
    {
        //
    }
}