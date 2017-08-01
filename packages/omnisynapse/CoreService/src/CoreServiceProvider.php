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
        $this->mergeConfigFrom(__DIR__.'/config.php', 'core');

        $this->app->singleton(CoreService::class, function () {
            return new CoreServiceImpl(config('core'));
        });
    }
}
