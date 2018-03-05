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
        $this->app->bind(Repositories\TimeframeRepository::class,
            Implementation\TimeframeRepositoryEloquent::class);
        $this->app->bind(Repositories\RoleRepository::class,
            Implementation\RoleRepositoryEloquent::class);
        $this->app->bind(Repositories\SpecialityRepository::class,
            Implementation\SpecialityRepositoryEloquent::class);
        $this->app->bind(Repositories\TagRepository::class,
            Implementation\TagRepositoryEloquent::class);
        $this->app->bind(Repositories\OperatorRepository::class,
            Implementation\OperatorRepositoryEloquent::class);
        $this->app->bind(Repositories\OfferLinkRepository::class,
            Implementation\OfferLinkRepositoryEloquent::class);

        $this->app->alias(Repositories\OfferRepository::class, 'offerRepository');
    }
}
