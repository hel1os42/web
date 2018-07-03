<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\PlaceService;
use App\Services\UserService;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Tymon\JWTAuth\Facades\JWTAuth;

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
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     * @param UserService $userService
     * @param AuthManager $authManager
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(UserRepository $userRepository, UserService $userService, AuthManager $authManager)
    {
        $this->userRepository = $userRepository;
        $this->userService    = $userService;

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

        $this->authorize('user.update', [$editableUser, $userData]);

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
     *
     * @return Response
     * @throws UnprocessableEntityHttpException
     * @throws \LogicException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Requests\Auth\RegisterRequest $request)
    {
        $newUserData = $this->prepareRegistrationData($request);

        $userService = $this->userService->setIssuer(auth()->user());

        DB::beginTransaction();

        try {

            $user = request()->wantsJson()
                ? $userService->make($newUserData)
                : $userService->register($newUserData);
        } catch (ValidationException $exception) {
            DB::rollBack();

            throw $exception;
        } catch (\Exception $exception) {
            DB::rollBack();

            logger()->error(sprintf('User registration failed. %1$s', $exception->getMessage()), [
                'exception' => $exception->getTrace(),
                'data'      => $newUserData,
            ]);

            throw new UnprocessableEntityHttpException('Something went wrong');
        }

        DB::commit();

        if (false === $user->exists) {
            throw new UnprocessableEntityHttpException('Something went wrong');
        }

        $view = $request->getRegistrator() instanceof User
            ? 'user.show'
            : 'auth.registered';

        $userData = $user->toArray();

        $userData['token']                = JWTAuth::fromUser($user);
        $userData['was_recently_created'] = $user->wasRecentlyCreated;

        return response()->render(
            $view,
            $userData,
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
