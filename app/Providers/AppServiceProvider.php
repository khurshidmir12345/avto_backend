<?php

namespace App\Providers;

use App\Models\MoshinaElon;
use App\Repositories\MoshinaElonRepository;
use App\Services\MoshinaElonImageService;
use App\Services\MoshinaElonService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MoshinaElonRepository::class, fn () => new MoshinaElonRepository(app(MoshinaElon::class)));
        $this->app->singleton(MoshinaElonImageService::class);
        $this->app->singleton(MoshinaElonService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
