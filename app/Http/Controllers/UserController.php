<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

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
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function create()
    {
        $this->authorize('users.create');

        return \response()->render('user.create', []);
    }

    /**
     * User profile show
     *
     * @param string|null $uuid
     *
     * @return Response
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
     * @param Requests\UserUpdateRequest $request
     * @param string|null                $uuid
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function update(Requests\UserUpdateRequest $request, string $uuid = null): Response
    {
        $uuid = $this->getUuid($uuid);

        $editableUser = $this->userRepository->find($uuid);

        $this->authorize('users.update', $editableUser);

        $userData = $request->all();

        if ($request->isMethod('put')) {
            $userData = \array_merge(\App\Helpers\Attributes::getFillableWithDefaults($editableUser,
                ['password']),
                $userData);
        }

        $user = $this->userRepository;

        $with = [];

        if ($this->user()->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT])) {
            $with = ['parents', 'children', 'roles'];
        }
        $user   = $user->update($userData, $uuid);
        $result = $user->fresh($with);

        if ($request->has('parent_ids')) {
            $result = $this->setParents($request->parent_ids, $user);
        }

        if ($request->has('child_ids')) {
            $result = $this->setChildren($request->child_ids, $user);
        }

        if ($request->has('role_ids')) {
            $result = $this->updateRoles($request->role_ids, $user);
        }

        if ($request->has('approve')) {
            $this->approve($user, $request->approve);
        }

        return \response()->render('user.show', $result, Response::HTTP_CREATED, route('profile'));
    }

    /**
     * @param Requests\Auth\RegisterRequest $request
     *
     * @return Response
     * @throws UnprocessableEntityHttpException
     * @throws \LogicException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function register(Requests\Auth\RegisterRequest $request)
    {
        $this->authorize('users.create');

        $newUserData = $request->all();

        $registrator = $request->getRegistrator();
        if (null !== $registrator) {
            $newUserData['referrer_id'] = $registrator->id;
        }

        $user = $this->userRepository->create($newUserData);

        $success = $user->exists;

        if (!$success) {
            throw new UnprocessableEntityHttpException();
        }

        return response()->render(
            null !== $registrator ? 'user.show' : 'auth.registered',
            $user->fresh(),
            Response::HTTP_CREATED,
            route('users.show', [$user->getId()]));
    }

    /**
     * @param string|null $uuid
     *
     * @return mixed
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
        $user->save();

        return $user->fresh();
    }

    /**
     * @param User $user
     * @param bool $approve
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    private function approve(User $user, bool $approve = true)
    {
        $this->authorize('users.update.approve', $user);

        $user->setApproved($approve)->save();
    }
}
