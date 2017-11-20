<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 09.10.2017
 * Time: 12:36
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Auth\AuthManager;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;
    /**
     * @var JWTAuth
     */
    protected $jwtAuth;

    /**
     * AuthController constructor.
     *
     * @param UserRepository $userRepository
     * @param JWTAuth        $jwtAuth
     * @param AuthManager    $auth
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(UserRepository $userRepository, JWTAuth $jwtAuth, AuthManager $auth)
    {
        $this->userRepository = $userRepository;
        $this->jwtAuth        = $jwtAuth;

        parent::__construct($auth);
    }
}
