<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProfileController extends Controller
{
    private $userRepository;
    private $auth;

    public function __construct(UserRepository $userRepository, AuthManager $authManager)
    {
        $this->userRepository = $userRepository;
        $this->auth           = $authManager;
    }


    /**
     * @return \Illuminate\Http\RedirectResponse|Response
     * @throws \InvalidArgumentException
     */
    public function index()
    {
        return \redirect()->route('profile');
    }

    /**
     * User profile show
     *
     * @param Request     $request
     * @param string|null $uuid
     *
     * @return Response
     * @throws HttpException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function show(Request $request, string $uuid = null): Response
    {
        $uuid = $this->checkUuid($uuid);

        $user = $this->userRepository->find($uuid);

        return \response()->render('profile', $user->toArray());
    }

    /**
     * @param ProfileUpdateRequest $request
     * @param string|null          $uuid
     *
     * @return Response
     * @throws HttpException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function update(ProfileUpdateRequest $request, string $uuid = null): Response
    {
        $uuid = $this->checkUuid($uuid);

        $userData = $request->all();

        if ($request->isMethod('put')) {
            $userData = \array_merge(User::getFillableWithDefaults(['password']), $userData);
        }

        $user = $this->userRepository->update($userData, $uuid);

        return \response()->render('profile', $user->toArray(), Response::HTTP_CREATED, route('profile'));
    }

    /**
     * @param string|null $uuid
     *
     * @return Response
     * @throws HttpException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function referrals(string $uuid = null)
    {
        $uuid = $this->checkUuid($uuid);

        $user = $this->userRepository->find($uuid);

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
}
