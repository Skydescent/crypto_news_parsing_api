<?php

namespace App\Providers;

use App\Contracts\Repositories\ArticleRepositoryContract;
use App\Repositories\ArticleRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            ArticleRepositoryContract::class,
            ArticleRepository::class
        );
    }
}
