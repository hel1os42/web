<?php

namespace App\Providers;

use App\Repositories;
use App\Repositories\Implementation;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * NS: App\Providers
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
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
        $this->app->bind(Repositories\AccountRepository::class,
            Implementation\AccountRepositoryEloquent::class);
        $this->app->bind(Repositories\TransactionRepository::class,
            Implementation\TransactionRepositoryEloquent::class);
        $this->app->bind(Repositories\RedemptionRepository::class,
            Implementation\RedemptionRepositoryEloquent::class);
    }
}
