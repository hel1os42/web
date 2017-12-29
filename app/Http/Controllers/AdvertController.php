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

class AdvertController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository, AuthManager $authManager)
    {
        $this->userRepository = $userRepository;

        parent::__construct($authManager);
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
    public function profile(): Response
    {
        $user = auth()->user();
        $user->load(['parents', 'children']);

        $this->authorize('users.show', $user);

        return \response()->render('advert.profile.show', $user->toArray());
    }
}
