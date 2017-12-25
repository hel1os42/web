<?php

namespace App\Http\Controllers;

use App\Helpers\Attributes;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository, AuthManager $authManager)
    {
        $this->userRepository = $userRepository;

        parent::__construct($authManager);
    }


    /**
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function index()
    {
        $this->authorize('users.list');

        $users = $this->user()->hasRoles([Role::ROLE_ADMIN])
            ? $this->userRepository->with('roles')
            : $this->user()->children()->with('roles');

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
        $uuid = $this->getUuid($uuid);

        $user = $this->userRepository->with('roles')->with('parents')->with('children')->find($uuid);

        $this->authorize('users.show', $user);

        return \response()->render('user.show', $user->toArray());
    }

    /**
     * @param UserUpdateRequest $request
     * @param string|null       $uuid
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

        $this->authorize('users.update', $this->userRepository->find($uuid));

        $userData = $request->all();

        if ($request->isMethod('put')) {
            $userData = \array_merge(Attributes::getFillableWithDefaults($this->user(), ['password']),
                $userData);
        }

        $user = $this->userRepository->with('roles');

        if ($this->user()->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT])) {
            $user->with('parents')->with('children');
        }
        $user = $user->update($userData, $uuid);

        if ($request->has('parent_ids')) {
            $this->setParents($request->parent_ids, $user);
        }

        if ($request->has('child_ids')) {
            $this->setChildren($request->child_ids, $user);
        }

        if ($request->has('role_ids')) {
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

        $this->authorize('users.referrals.list', $user);

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
        return null === $uuid ? $this->guard->id() : $uuid;
    }

    /**
     * @param array $userIds
     * @param User  $user
     *
     * @return User
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     */
    private function setChildren(array $userIds, User $user): User
    {
        $this->authorize('users.update.children', $user);

        $user->children()->sync($userIds, true);
        $user->save();

        return $user->fresh();
    }

    /**
     * @param array $userIds
     * @param User  $user
     *
     * @return User
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     */
    private function setParents(array $userIds, User $user): User
    {
        $this->authorize('users.update.parents', $user);

        $user->parents()->sync($userIds, true);
        $user->save();

        return $user->fresh();
    }

    /**
     * @param array $roleIds
     * @param User  $user
     *
     * @return User
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    private function updateRoles(array $roleIds, User $user): User
    {
        $this->authorize('users.update.roles', $user);

        $user->roles()->sync($roleIds, true);

        return $user->fresh();
    }
}
