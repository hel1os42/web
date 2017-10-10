<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    private $userRepository;
    private $auth;

    public function __construct(UserRepository $userRepository, AuthManager $authManager)
    {
        $this->userRepository = $userRepository;
        $this->auth           = $authManager;
    }

    /**
     * @return Response
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function index(): Response
    {
        return \response()->render('admin.users.list', $this->userRepository->with('roles')->all()->paginate());
    }
}
