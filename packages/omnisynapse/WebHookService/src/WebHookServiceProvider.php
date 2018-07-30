<?php

namespace OmniSynapse\WebHookService;

use App\Models\ActivationCode;
use App\Models\NauModels\Offer;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;
use OmniSynapse\WebHookService\Contracts\WebHookService as WebHookServiceContract;
use OmniSynapse\WebHookService\Models\WebHook;
use OmniSynapse\WebHookService\Observers\ActivationCodeObserver;
use OmniSynapse\WebHookService\Observers\OfferObserver;
use OmniSynapse\WebHookService\Policies\WebHookPolicy;
use OmniSynapse\WebHookService\Repositories\Contracts\WebHookEventRepository;
use OmniSynapse\WebHookService\Repositories\Contracts\WebHookRepository;
use OmniSynapse\WebHookService\Repositories\WebHookEventRepositoryEloquent;
use OmniSynapse\WebHookService\Repositories\WebHookRepositoryEloquent;

/**
 * Class WebHookServiceProvider
 * @package OmniSynapse\CoreService
 */
class WebHookServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @param Gate $gate
     * @return void
     */
    public function boot(Gate $gate)
    {
        Offer::observe(OfferObserver::class);
        ActivationCode::observe(ActivationCodeObserver::class);

        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');

        $gate->policy(WebHook::class, WebHookPolicy::class);
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->app->bind(WebHookServiceContract::class, WebHookService::class);
        $this->app->bind(WebHookRepository::class, WebHookRepositoryEloquent::class);
        $this->app->bind(WebHookEventRepository::class, WebHookEventRepositoryEloquent::class);
    }
}
