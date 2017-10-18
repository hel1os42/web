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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function usersList(): Response
    {
        $this->authorize('adminUserList', $this->userRepository->model());

        return \response()->render('admin.users.index', $this->userRepository->with('roles')->paginate());
    }

    public function setChildren(): Response
    {
        $this->authorize('adminSetChildren', $this->userRepository->model());

        // setChildren

        return \response()->render('admin.users.index', $this->userRepository->with('roles')->paginate());
    }

    public function setParents(): Response
    {
        $this->authorize('adminSetParents', $this->userRepository->model());

        // setParents

        return \response()->render('admin.users.index', $this->userRepository->with('roles')->paginate());
    }

    public function updateRoles(): Response
    {
        $this->authorize('adminUpdateRoles', $this->userRepository->model());

        // updateRoles

        return \response()->render('admin.users.index', $this->userRepository->with('roles')->paginate());
    }
}
