<?php

namespace App\Providers;

use App\Contracts\Services\ArticleServiceContract;
use App\Contracts\Services\GetArticlesFromApiServiceContract;
use App\Services\ArticleService;
use App\Services\GetArticlesFromApiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            GetArticlesFromApiServiceContract::class,
            GetArticlesFromApiService::class
        );

        $this->app->singleton(ArticleServiceContract::class, ArticleService::class);
    }
}
