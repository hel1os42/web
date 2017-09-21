<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Symfony\Component\Debug\Exception\FlattenException;
use Tymon\JWTAuth\Exceptions as JwtException;

class Handler extends ExceptionHandler
{

    /**
     * @param Exception $exception
     *
     * @return Response
     * @throws \LogicException
     */
    protected function convertExceptionToResponse(Exception $exception)
    {
        $exception = FlattenException::create($exception);

        return response()->error($exception->getStatusCode(), $exception->getMessage());
    }

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
        if ($exception instanceof JwtException\JWTException) {
            return response()->json(trans($exception->getMessage()), $exception->getStatusCode());
        } elseif ($exception instanceof JwtException\TokenExpiredException) {
            return response()->json(trans($exception->getMessage()), $exception->getStatusCode());
        } elseif ($exception instanceof JwtException\TokenInvalidException) {
            return response()->json(trans($exception->getMessage()), $exception->getStatusCode());
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
        if (request()->expectsJson()) {
            return response()->error($response->getStatusCode(), $exception->getMessage());
        }

        return parent::toIlluminateResponse($response, $exception);
    }
}
