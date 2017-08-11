<?php

namespace App\Providers;

use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use App\Models\NauModels\Transact;
use App\Models\User;
use App\Observers\OfferObserver;
use App\Observers\RedemptionObserver;
use App\Observers\TransactObserver;
use App\Observers\UserObserver;
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
        User::observe(UserObserver::class);
        Offer::observe(OfferObserver::class);
        Transact::observe(TransactObserver::class);
        Redemption::observe(RedemptionObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
