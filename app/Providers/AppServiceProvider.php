<?php

namespace App\Providers;

use App\Models\NauModels\Offer;
use App\Models\User;
use App\Observers\OfferObserver;
use App\Observers\UserObserver;
use App\Repositories\Criteria\MappableRequestCriteria;
use App\Repositories\Criteria\MappableRequestCriteriaEloquent;
use App\Services\Implementation\InvestorAreaService as InvestorAreaServiceImpl;
use App\Services\Implementation\NauOfferReservation;
use App\Services\Implementation\WeekDaysService as WeekDaysServiceImpl;
use App\Services\InvestorAreaService;
use App\Services\NauOffersService;
use App\Services\OfferReservation;
use App\Services\OffersService;
use App\Services\WeekDaysService;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package App\Providers
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
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
            'users' => User::class
        ]);

        Offer::observe(OfferObserver::class);
        User::observe(UserObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            OffersService::class,
            NauOffersService::class);
        $this->app->bind(
            WeekDaysService::class,
            WeekDaysServiceImpl::class
        );
        $this->app->bind(
            OfferReservation::class,
            NauOfferReservation::class);
        $this->app->bind(
            MappableRequestCriteria::class,
            MappableRequestCriteriaEloquent::class
        );
        $this->app->bind(
            InvestorAreaService::class,
            InvestorAreaServiceImpl::class
        );
    }
}
