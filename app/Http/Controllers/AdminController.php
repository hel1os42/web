<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\SetChildrenRequest;
use App\Http\Requests\Admin\UpdateRolesRequest;
use App\Http\Requests\Admin\UsersListRequest;
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

    }

    public function usersRights(string $userId): Response
    {

    }

    public function setChildren(SetChildrenRequest $request, string $userId): Response
    {
        $this->authorize('adminSetChildren', $this->userRepository->model());

        $user = $this->userRepository->find($userId);

        $user->children()->detach();

        $user->children()->attach($request->user_ids);

        return \response()->render('admin.users.rights', $user->toArray(), Response::HTTP_CREATED, route('admin.users.show'));
    }

    public function setParents(SetChildrenRequest $request, string $userId): Response
    {
        $this->authorize('adminSetParents', $this->userRepository->model());

        $user = $this->userRepository->find($userId);

        $user->parents()->detach();

        $user->parents()->attach($request->user_ids);
    }

    public function updateRoles(UpdateRolesRequest $request, string $userId): Response
    {
        $this->authorize('adminUpdateRoles', $this->userRepository->model());

        $user = $this->userRepository->find($userId);

        $user->roles()->detach();

        $user->roles()->attach($request->user_ids);

        return \response()->render('admin.users.rights', $user->toArray(), Response::HTTP_CREATED, route('admin.users.show'));
    }
}
