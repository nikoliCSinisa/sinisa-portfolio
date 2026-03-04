<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Portfolio\Repositories\ProjectRepositoryInterface;
use App\Modules\Portfolio\Repositories\EloquentProjectRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProjectRepositoryInterface::class, EloquentProjectRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
