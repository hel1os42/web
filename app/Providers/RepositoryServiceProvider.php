<?php

namespace App\Providers;

use App\Repositories;
use App\Repositories\Implementation;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * NS: App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Repositories\UserRepository::class,
            Implementation\UserRepositoryEloquent::class);
        $this->app->bind(Repositories\ActivationCodeRepository::class,
            Implementation\ActivationCodeRepositoryEloquent::class);
        $this->app->bind(Repositories\OfferRepository::class,
            Implementation\OfferRepositoryEloquent::class);
        $this->app->bind(Repositories\CategoryRepository::class,
            Implementation\CategoryRepositoryEloquent::class);
        $this->app->bind(Repositories\PlaceRepository::class,
            Implementation\PlaceRepositoryEloquent::class);
    }
}
