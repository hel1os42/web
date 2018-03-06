<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class Controller
 * @package App\Http\Controllers
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var AuthManager $auth
     */
    protected $auth;

    /**
     * @var \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    public function __construct(AuthManager $authManager)
    {
        $this->auth  = $authManager;
        $this->guard = $this->auth->guard();
    }

    /**
     * @return User
     */
    protected function user(): User
    {
        return $this->guard->user();
    }

    /**
     * @param null|string $uuid
     *
     * @return string
     */
    protected function confirmUuid(?string $uuid): string
    {
        return null === $uuid ? $this->user()->getId() : $uuid;
    }
}
