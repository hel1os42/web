<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Tymon\JWTAuth\Exceptions\JWTException;

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
        \Tymon\JWTAuth\Exceptions\TokenExpiredException::class,
    ];

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     * @throws \LogicException
     */
    protected function unauthenticated($request)
    {
        if ($request->expectsJson()) {
            return response()->error(Response::HTTP_UNAUTHORIZED);
        }

        return redirect()->guest(route('loginForm'));
    }

    /**
     * Render an exception into a response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Exception                 $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof JWTException) {
            return response()->json(trans($exception->getMessage()), Response::HTTP_UNAUTHORIZED);
        }
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return \response()->error(Response::HTTP_NOT_FOUND);
        }

        return parent::render($request, $exception);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param Exception                                  $exception
     *
     * @return Response
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    protected function toIlluminateResponse($response, Exception $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            $exception = new TokenMismatchException("CsRf Token Mismatch", $exception->getCode(), $exception);
        }

        if (request()->expectsJson()) {
            return response()->error($response->getStatusCode(), $exception->getMessage());
        }

        if ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withErrors(['msg', $exception->getMessage()]);
        }

        return parent::toIlluminateResponse($response, $exception);
    }
}
