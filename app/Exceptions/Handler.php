<?php

namespace App\Exceptions;

use App\Exceptions\Offer\Redemption\BadActivationCodeException;
use App\Exceptions\Offer\Redemption\CannotRedeemException;
use App\Exceptions\TokenException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];


    /**
     * @param Exception $e
     * @return Response
     */
    protected function convertExceptionToResponse(Exception $e): Response
    {
        $e = FlattenException::create($e);

        return response()->error($e->getStatusCode(), $e->getMessage());
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request)
    {
        if ($request->expectsJson()) {
            return response()->error(Response::HTTP_UNAUTHORIZED);
        }

        return redirect()->guest(route('loginForm'));
    }
}
