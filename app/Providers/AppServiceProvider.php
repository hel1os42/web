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
        //
    }
}
