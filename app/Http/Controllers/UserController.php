<?php

namespace App\Http\Controllers;

use App\Helpers\Attributes;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Role;
use App\Repositories\UserRepository;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
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
    public function index()
    {
        $this->authorize('index', $this->userRepository->model());

        $users = $this->auth->user()->hasRoles([Role::ROLE_ADMIN])
            ? $this->userRepository->with('roles')
            : $this->auth->user()->children()->with('roles');

        return \response()->render('user.index', $users->paginate());
    }

    /**
     * User profile show
     *
     * @param string|null $uuid
     *
     * @return Response
     * @throws HttpException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function show(string $uuid = null): Response
    {
        $uuid = $this->checkUuid($uuid);

        $user = $this->userRepository->find($uuid);

        $this->authorize('show', $user);

        return \response()->render('profile', $user->toArray());
    }

    /**
     * @param UserUpdateRequest $request
     * @param string|null          $uuid
     *
     * @return Response
     * @throws HttpException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function update(UserUpdateRequest $request, string $uuid = null): Response
    {
        $uuid = $this->checkUuid($uuid);

        $this->authorize('update', $this->userRepository->find($uuid));

        $userData = $request->all();

        if ($request->isMethod('put')) {
            $userData = \array_merge(Attributes::getFillableWithDefaults($this->auth->guard()->user(), ['password']),
                $userData);
        }

        $user = $this->userRepository->update($userData, $uuid);

        if($request->has('parent_ids')) {
            $this->setParents($request->parent_ids, $user);
        }

        if($request->has('child_ids')) {
            $this->setChildren($request->child_ids, $user);
        }

        if($request->has('role_ids')) {
            $this->updateRoles($request->role_ids, $user);
        }

        return \response()->render('profile', $user->toArray(), Response::HTTP_CREATED, route('profile'));
    }

    /**
     * @param string|null $uuid
     *
     * @return mixed
     * @throws HttpException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function referrals(string $uuid = null)
    {
        $uuid = $this->checkUuid($uuid);

        $user = $this->userRepository->find($uuid);

        $this->authorize('referrals', $user);

        return \response()->render('user.profile.referrals', $user->referrals()->paginate());
    }

    /**
     * @param string $uuid
     *
     * @return int|null|string
     * @throws HttpException
     * @throws \InvalidArgumentException
     */
    private function checkUuid(?string $uuid)
    {
        $currentId = $this->auth->guard()->id();
        if (null === $uuid) {
            $uuid = $currentId;
        } elseif ($uuid !== $currentId) {
            throw new HttpException(Response::HTTP_FORBIDDEN);
        }

        return $uuid;
    }

    /**
     * @param array $user_ids
     * @param $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function setChildren(array $user_ids, $user)
    {
        $this->authorize('setChildren', $user);

        $user->children()->detach();

        $user->children()->attach($user_ids);
    }

    /**
     * @param array $user_ids
     * @param $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function setParents(array $user_ids, $user)
    {
        $this->authorize('setParents', $user);

        $user->parents()->detach();

        $user->parents()->attach($user_ids);
    }

    /**
     * @param array $role_ids
     * @param $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function updateRoles(array $role_ids, $user)
    {
        $this->authorize('updateRoles', $user);

        $user->roles()->detach();

        $user->roles()->attach($role_ids);
    }
}
