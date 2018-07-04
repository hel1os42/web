<?php

namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChildController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * ChildController constructor.
     *
     * @param AuthManager    $authManager
     * @param UserRepository $userRepository
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(AuthManager $authManager, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        parent::__construct($authManager);
    }

    /**
     * @param Request $request
     * @param string  $userId
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function index(Request $request, string $userId): Response
    {
        $user = $this->userRepository->find($userId);

        $this->authorize('user.children.list', $user);

        $children = $this->userRepository->getChildrenByUsers([$userId])->with('roles:name');
        $perPage  = abs((int)$request->get('per_page', 15));

        return \response()->render('user.children.index', $children->paginate($perPage));
    }

    /**
     * @param Requests\User\ChildRequest $request
     * @param string                     $userId
     *
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Requests\User\ChildRequest $request, string $userId): RedirectResponse
    {
        $user        = $this->userRepository->find($userId);
        $childrenIds = $request['children_ids'];

        $this->authorize('user.children.update', [$user, $childrenIds]);

        if ($this->user()->isAdmin()) {
            $childrenIds = $this->includeRelatives($user, $childrenIds, $request->method());
        }

        $saveMethod = $request->isMethod('put') ? 'sync' : 'syncWithoutDetaching';
        $user->children()->$saveMethod($childrenIds, true);

        if ($request->wantsJson()) {
            return \response()->render('user.show', $user->toArray(), Response::HTTP_OK,
                route('users.show', [$userId]));
        }

        return \redirect()->back();
    }

    /**
     * @param Requests\User\ChildRequest $request
     * @param string                     $userId
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Requests\User\ChildRequest $request, string $userId): Response
    {
        $user        = $this->userRepository->find($userId);
        $childrenIds = $request['children_ids'];

        $this->authorize('user.children.update', [$user, $childrenIds]);

        if ($this->user()->isAdmin()) {
            $grandChildren = $this->getChildrenByUsers($childrenIds)->pluck('id');
            $childrenIds   = array_merge($childrenIds, $grandChildren->toArray());
            $this->updateAllParentsWithChildren($user, $childrenIds, 'detach');
        }

        $user->children()->detach($childrenIds);

        return response(null, Response::HTTP_RESET_CONTENT);
    }

    /**
     * @param User   $user
     * @param array  $childIds
     * @param string $method
     *
     * @return array
     */
    private function includeRelatives(User $user, array $childIds, string $method): array
    {
        if ($method === 'PUT') {
            $removedUsersIds = $this->getChildrenByUsers([$user->getId()])->pluck('id')->diff($childIds);

            $grandChildrenOfDetachedUsers = $this->getChildrenByUsers($removedUsersIds)->pluck('id');
            $this->updateAllParentsWithChildren(
                $user,
                $grandChildrenOfDetachedUsers->merge($removedUsersIds),
                'detach'
            );
            // exclude grandchildren of detached users
            $childIds = array_diff($childIds, $grandChildrenOfDetachedUsers->toArray());
        }

        $grandChildren = $this->getChildrenByUsers($childIds)
            ->pluck('id');
        // accept grandchildren
        $childIds = array_merge($childIds, $grandChildren->toArray());

        $this->updateAllParentsWithChildren($user, $childIds, 'syncWithoutDetaching');

        return $childIds;
    }

    /**
     * @param  mixed $usersIds
     * @return UserRepository
     */
    private function getChildrenByUsers($usersIds): UserRepository
    {
        return app(UserRepository::class)->getChildrenByUsers($usersIds);
    }

    /**
     * @param User   $editableUser
     * @param mixed  $childrenIds
     * @param string $method
     */
    private function updateAllParentsWithChildren(User $editableUser, $childrenIds, string $method)
    {
        $parents = $editableUser->parents()->get();

        foreach ($parents as $user) {
            $user->children()->$method($childrenIds);
        }
    }
}
