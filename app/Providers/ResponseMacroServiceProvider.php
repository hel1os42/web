<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response as HTTPResponse;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('render', function (string $view, $params = [], int $statusCode = null) {
            if (null === $statusCode) {
                $statusCode = HTTPResponse::HTTP_OK;
            }

            if (request()->wantsJson()) {
                return response()->json($params, $statusCode);
            }

            return response()->view($view, $params, $statusCode);
        });

        Response::macro('error', function (int $statusCode, string $message = null) {

            if (empty($message)) {
                switch ($statusCode) {
                    case HTTPResponse::HTTP_UNAUTHORIZED:
                        $message = trans('errors.401');
                        break;
                    case HTTPResponse::HTTP_FORBIDDEN:
                        $message = trans('errors.403');
                        break;
                    case HTTPResponse::HTTP_NOT_FOUND:
                        $message = trans('errors.404');
                        break;
                }
            }

            if (!request()->wantsJson()) {
                session()->flash('error',$message);
                abort($statusCode);
            }

            return response()->json(['error' => $message], $statusCode);
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
