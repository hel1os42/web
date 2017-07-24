<?php

namespace OmniSynapse\CoreService;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config.php', 'core-config');

        $this->app->singleton(CoreServiceInterface::class, function () {
            return new CoreService(config('core-config'));
        });
    }
}
