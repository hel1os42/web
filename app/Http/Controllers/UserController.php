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
        //dd(request()->method());
        $uuid = $this->getUuid($uuid);

        $user = $this->userRepository->with('roles')->with('parents')->with('children')->find($uuid);

        $this->authorize('show', $user);

        return \response()->render('user.show', $user->toArray());
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
        $uuid = $this->getUuid($uuid);

        $this->authorize('update', $this->auth->guard()->user(), $this->userRepository->find($uuid));

        $userData = $request->all();

        if ($request->isMethod('put')) {
            $userData = \array_merge(Attributes::getFillableWithDefaults($this->auth->guard()->user(), ['password']),
                $userData);
        }

        $user = $this->userRepository->with('roles');

        if ($this->auth->guard()->user()->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT])) {
            $user->with('parents')->with('children');
        }
        $user = $user->update($userData, $uuid);

        if($request->has('parent_ids')) {
            $this->setParents($request->parent_ids, $user);
        }

        if($request->has('child_ids')) {
            $this->setChildren($request->child_ids, $user);
        }

        if($request->has('role_ids')) {
            $this->updateRoles($request->role_ids, $user);
        }

        return \response()->render('user.show', $user->toArray(), Response::HTTP_CREATED, route('profile'));
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
        $uuid = $this->getUuid($uuid);

        $user = $this->userRepository->find($uuid);

        $this->authorize('referrals', $user);

        return \response()->render('user.profile.referrals', $user->referrals()->paginate());
    }

    /**
     * @param null|string $uuid
     *
     * @return int|null|string
     * @throws \InvalidArgumentException
     */
    private function getUuid(?string $uuid)
    {
        return null === $uuid ? $this->auth->guard()->id() : $uuid;
    }

    /**
     * @param array $user_ids
     * @param       $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     */
    private function setChildren(array $user_ids, $user)
    {
        $this->authorize('setChildren', $this->auth->guard()->user(), $user);

        $user->children()->detach();

        $user->children()->attach($user_ids);

        $user->save();
    }

    /**
     * @param array $user_ids
     * @param       $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     */
    private function setParents(array $user_ids, $user)
    {
        $this->authorize('setParents', $this->auth->guard()->user(), $user);

        $user->parents()->detach();

        $user->parents()->attach($user_ids);

        $user->save();
    }

    /**
     * @param array $role_ids
     * @param $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     */
    private function updateRoles(array $role_ids, $user)
    {

        $this->authorize('updateRoles', $this->auth->guard()->user(), $user);

        $user->roles()->detach();

        $user->roles()->attach($role_ids);

        $user->save();
    }
}
