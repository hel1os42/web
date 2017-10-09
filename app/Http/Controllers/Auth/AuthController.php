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
    protected $userRepository;
    protected $jwtAuth;
    protected $auth;

    public function __construct(UserRepository $userRepository, JWTAuth $jwtAuth, AuthManager $auth)
    {
        $this->userRepository = $userRepository;
        $this->jwtAuth        = $jwtAuth;
        $this->auth           = $auth;
    }
}
