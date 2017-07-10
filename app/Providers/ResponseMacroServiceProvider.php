<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('render', function ($view, $params=[], $status=null) {
            if (null === $status) {
                $status = \Illuminate\Http\Response::HTTP_OK;
            }

            if (request()->wantsJson()) {
                return response()->json($params, $status);
            }

            return response()->view($view, $params, $status);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
