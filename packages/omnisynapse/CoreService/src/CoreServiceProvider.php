<?php

namespace OmniSynapse\CoreService;

use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use App\Models\NauModels\Transact;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use OmniSynapse\CoreService\Observers\OfferObserver;
use OmniSynapse\CoreService\Observers\RedemptionObserver;
use OmniSynapse\CoreService\Observers\TransactObserver;
use OmniSynapse\CoreService\Observers\UserObserver;

class CoreServiceProvider extends ServiceProvider
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
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config.php', 'core');

        $this->app->singleton(CoreService::class, function () {
            return new CoreServiceImpl(config('core'));
        });
    }
}
