<?php

namespace App\Http\Middleware;

use App\Http\Exceptions\UnauthorizedException;
use App\Services\InvestorAreaService;
use Illuminate\Http\Request;

/**
 * Class Investor
 * NS: App\Http\Middleware
 */
class Investor
{
    /**
     * @var InvestorAreaService
     */
    private $service;

    public function __construct(InvestorAreaService $service) {
        $this->service = $service;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     * @throws UnauthorizedException
     */
    public function handle(Request $request, \Closure $next)
    {
        if (!$this->service->checkRequestSign($request)) {
            throw new UnauthorizedException();
        }

        return $next($request);
    }
}