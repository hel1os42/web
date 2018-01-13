<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\PlaceService;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     * @param AuthManager    $authManager
     *
     * @throws \InvalidArgumentException
     */
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

        $users = $this->user()->isAdmin()
            ? $this->userRepository->with(['roles', 'accounts', 'place'])
            : $this->user()->children()->with(['roles', 'accounts', 'place']);

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

        return \response()->render('user.create', Requests\Auth\RegisterRequest::preFilledFormRequest());
    }

    /**
     * @param string|null $uuid
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function show(string $uuid = null): Response
    {
        $uuid = $this->confirmUuid($uuid);

        $with = ['place'];

        if ($this->user()->isAdmin() || $this->user()->isAgent()) {
            $with = array_merge($with, ['roles', 'parents', 'children']);
        }

        $user = $this->userRepository->with($with)->find($uuid);

        $this->authorize('users.show', $user);


        return \response()->render('user.show', $user->toArray());
    }

    /**
     * @param Requests\UserUpdateRequest $request
     * @param string|null                $uuid
     * @param PlaceService               $placeService
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function update(
        Requests\UserUpdateRequest $request,
        PlaceService $placeService,
        string $uuid = null
    ): Response {
        $uuid = $this->confirmUuid($uuid);

        $editableUser = $this->userRepository->find($uuid);
        $userData     = $request->isMethod('put')
            ? $request->all()
            : array_merge($editableUser->getFillableWithDefaults(['password']), $request->all());

        $this->authorize('users.update', [$editableUser, $userData]);

        if (isset($userData['approved']) && $userData['approved'] === false) {
            $placeService->disapprove($editableUser->place);
        }

        $user = $this->userRepository;
        $user = $user->update($userData, $uuid);
        $user = $this->updateRelationData($user, $userData);

        return \response()->render('user.show', $user->toArray(), Response::HTTP_CREATED, route('profile'));
    }

    /**
     * @param Requests\Auth\RegisterRequest $request
     *
     * @return Response
     * @throws UnprocessableEntityHttpException
     * @throws \LogicException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Requests\Auth\RegisterRequest $request)
    {
        $newUserData = $request->except('approved');

        $registrator = $request->getRegistrator();

        $view = 'auth.registered';

        if (null !== $registrator) {
            $view                       = 'user.show';
            $newUserData['referrer_id'] = $registrator->id;
            $this->authorize('users.create', [$newUserData]);
        }

        $user = $this->userRepository->create($newUserData);

        $success = $user->exists;

        if (!$success) {
            throw new UnprocessableEntityHttpException();
        }

        $user = null !== $registrator ? $this->createRelationData($user, $newUserData) : $user;

        return response()->render(
            $view,
            $user->toArray(),
            Response::HTTP_CREATED,
            route('users.show', [$user->getId()])
        );
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
        $uuid = $this->confirmUuid($uuid);

        $user = $this->userRepository->find($uuid);

        $this->authorize('users.referrals.list', $user);

        return \response()->render('user.profile.referrals', $user->referrals()->paginate());
    }

    /**
     * @param User  $user
     * @param array $newUserData
     *
     * @return User
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    private function createRelationData(User $user, array $newUserData): User
    {
        $with = [];

        if (isset($newUserData['role_ids'])) {
            $this->updateRoles($user, $newUserData['role_ids']);
            array_push($with, 'roles');
        }

        if (isset($newUserData['parent_ids'])) {
            $this->authorize('user.update.parents', [$user, $newUserData['parent_ids']]);
            $user->parents()->attach($newUserData['parent_ids']);
            array_push($with, 'parents');
        }

        if (!empty($with)) {
            $user->save();

            return $user->fresh($with);
        }

        return $user;
    }

    /**
     * @param User  $user
     * @param array $newUserData
     *
     * @return User
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    private function updateRelationData(User $user, array $newUserData): User
    {
        $with = [];

        if (isset($newUserData['role_ids'])) {
            $this->updateRoles($user, $newUserData['role_ids']);
            array_push($with, 'roles');
        }

        if (isset($newUserData['parent_ids'])) {
            $this->authorize('user.update.parents', [$user, $newUserData['parent_ids']]);
            $user->parents()->sync($newUserData['parent_ids'], true);
            array_push($with, 'parents');
        }

        if (isset($newUserData['child_ids'])) {
            $this->authorize('user.update.children', [$user, $newUserData['child_ids']]);
            $user->children()->sync($newUserData['child_ids'], true);
            array_push($with, 'parents');
        }

        if (!empty($with)) {
            $user->save();

            return $user->fresh($with);
        }

        return $user;
    }

    /**
     * @param User  $user
     * @param array $roleIds
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    private function updateRoles(User $user, array $roleIds)
    {
        $this->authorize('user.update.roles', [$user, $roleIds]);

        $user->roles()->sync($roleIds, true);
    }
}
