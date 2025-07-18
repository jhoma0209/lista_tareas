<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\TareaRepository;
use App\Services\TareaService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
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
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}