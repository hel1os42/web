<?php

namespace App\Providers;

use Illuminate\Http\Response as HTTPResponse;
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
        Response::macro('render', function (string $view, $params = [], int $statusCode = HTTPResponse::HTTP_OK, string $route = '') {
            if (request()->wantsJson()){
                if(($statusCode == HTTPResponse::HTTP_ACCEPTED || $statusCode == HTTPResponse::HTTP_CREATED) && !empty($route)){
                    return response()->json($params, $statusCode)->header('Location', $route);
                }
                return response()->json($params, $statusCode);
            }

                return response()->view($view, $params, $statusCode);
        });

        Response::macro('error', function (int $statusCode, string $message = null, string $location = '') {

            if (empty($message)) {
                $message = trans('errors.' . (string)$statusCode);
            }

            if (!request()->wantsJson()) {
                session()->flash('error', $message);
                abort($statusCode, $message);
            }

            $response = \response()->json(['error' => true, 'message' => $message], $statusCode);

            return empty($location) ? $response : $response->header('Location', $location);
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
