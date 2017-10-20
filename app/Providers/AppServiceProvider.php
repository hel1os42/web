<?php

namespace App\Providers;

use App\Repositories\Criteria\MappableRequestCriteria;
use App\Repositories\Criteria\MappableRequestCriteriaEloquent;
use App\Services\NauOffersService;
use App\Services\OffersService;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'users' => \App\Models\User::class
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OffersService::class, NauOffersService::class);
        $this->app->bind(
            \App\Services\WeekDaysService::class,
            \App\Services\Implementation\WeekDaysService::class
        );
        $this->app->bind(
            MappableRequestCriteria::class,
            MappableRequestCriteriaEloquent::class
        );
    }
}
