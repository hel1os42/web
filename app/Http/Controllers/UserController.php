<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\User;
use App\Models\Role;
use App\Repositories\UserRepository;
use App\Services\PlaceService;
use Carbon\Carbon;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
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
            ? $this->userRepository
            : $this->userRepository->getChildrenByUser($this->user());

        $requestedPerPage = request()->get('per_page');

        $perPage = $requestedPerPage > config('repository.pagination.max_limit')
            ? null
            : $requestedPerPage;

        return \response()->render('user.index', $users->with(['roles', 'accounts', 'place'])
            ->paginate($perPage));
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

        $userData = $request->isMethod('put')
            ? $request->all()
            : array_merge($editableUser->getFillableWithDefaults(['password', 'approved', 'invite_code']), $request->all());

        $this->authorize('users.update', [$editableUser, $userData]);

        if (isset($userData['approved']) && $userData['approved'] === false) {
            $placeService->disapprove($editableUser->place);
        }

        $user = $this->userRepository;
        $user = $user->update($userData, $uuid);
        $user = $this->updateRelationData($user, $userData);

        $with = ['place'];

        if ($this->user()->isAdmin() || $this->user()->isAgent()) {
            $with = array_merge($with, ['roles', 'parents', 'children']);
        }

        return \response()->render('user.show', $user->fresh($with), Response::HTTP_CREATED, route('profile'));
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
        $newUserData = $this->prepareRegistrationData($request);

        $user = $this->createUser($newUserData);

        if (false === $user->exists) {
            throw new UnprocessableEntityHttpException();
        }

        $view = $request->getRegistrator() instanceof User
            ? 'user.show'
            : 'auth.registered';

        return response()->render(
            $view,
            $user->toArray(),
            Response::HTTP_CREATED,
            route('users.show', [$user->getId()])
        );
    }

    /**
     * @param Requests\Auth\RegisterRequest $request
     * @return array
     */
    private function prepareRegistrationData(Requests\Auth\RegisterRequest $request): array
    {
        $newUserData = $request->except('approved');
        $registrator = $request->getRegistrator();

        if ($registrator instanceof User) {
            $newUserData['referrer_id'] = $registrator->getKey();
        }

        return $newUserData;
    }

    /**
     * @param array $newUserData
     *
     * @return User
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    private function createUser(array $newUserData): User
    {
        $lockTime = Carbon::now()->addSeconds(config('app.race_condition_lock_time'));

        $keyData = array_merge($newUserData, ['domain' => config('app.url')]);
        $lockKey = 'avoid-register-race-condition' . md5(json_encode($keyData));

        if (false === Cache::add($lockKey, 1, $lockTime)) {
            $registeredUser = $this->findRegisteredUser($newUserData);

            // for security reason we must re-validate uniqueness
            if (false === $registeredUser->exists) {
                $this->validateUserUniqueness($newUserData);
            }

            return $registeredUser;
        }

        $this->validateUserUniqueness($newUserData);

        $newUser = $this->userRepository->create($newUserData);

        if (false === $newUser->wasRecentlyCreated) {
            throw new UnprocessableEntityHttpException();
        }

        if (auth()->check() && $this->authorize('users.create', [$newUserData])) {
            $newUser = $this->createRelationData($newUser, $newUserData);
        }

        return $newUser;
    }

    /**
     * @param array $newUserData
     *
     * @return void
     */
    private function validateUserUniqueness(array $newUserData)
    {
        $uniquenessRules = [
            'email' => 'nullable|unique:users,email',
            'phone' => 'nullable|unique:users,phone',
        ];

        Validator::validate($newUserData, $uniquenessRules);
    }

    /**
     * @param array $userData
     *
     * @return User
     */
    private function findRegisteredUser(array $userData): User
    {
        $foundedUsers = $this->userRepository->scopeQuery(function ($query) use ($userData) {
            $conditions = array_only($userData, ['email', 'phone']);
            $query      = $this->getConditionSubQuery($query, $conditions);

            $from = Carbon::now()->subSeconds(config('app.race_condition_lock_time'));
            $to   = Carbon::now();

            $query = $query->whereBetween('created_at', array($from, $to));

            return $query;
        })->paginate(1);

        return $foundedUsers->isEmpty()
            ? new User()
            : $foundedUsers->first();
    }

    /**
     * @param User $model
     * @param array $conditions
     *
     * @return Builder
     */
    private function getConditionSubQuery(User $model, array $conditions): Builder
    {
        if (0 === count($conditions)) {
            return $model->newQuery();
        }

        return $model->where(function ($subQuery) use ($conditions) {
            foreach ($conditions as $field => $value) {
                $subQuery = $subQuery->orWhere($field, $value);
            }

            return $subQuery;
        });
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
     * @param User $user
     * @param array $newUserData
     *
     * @return User
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    private function updateRelationData(User $user, array $newUserData): User
    {
        $with = [];

        if (isset($newUserData['role_ids']) && count($newUserData['role_ids'])) {
            $this->updateRoles($user, $newUserData['role_ids']);
            array_push($with, 'roles');
        }

        if (isset($newUserData['parent_ids'])) {
            $this->authorize('user.update.parents', [$user, $newUserData['parent_ids']]);
            $user->parents()->sync($newUserData['parent_ids'], true);
            array_push($with, 'parents');
        }

        if ($this->user()->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT])
            && $this->user()->getId() !== $user->getId()) {

            $children = $newUserData['child_ids'] ?? [];
            $this->authorize('user.update.children', [$user, $children]);
            $user->children()->sync($children, true);
            array_push($with, 'children');
        }

        if (!empty($with)) {
            $user->save();

            return $user->fresh($with);
        }

        return $user;
    }

    /**
     * @param User $user
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
