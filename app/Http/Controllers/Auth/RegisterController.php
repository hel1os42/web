<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    /**
     * Return user register form
     *
     * @param string $invite
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function getRegisterForm(string $invite)
    {
        $referrerUser = new User();
        $referrerUser = $referrerUser->findByInvite($invite);
        if (!$referrerUser instanceof User) {
            return response()->error(Response::HTTP_NOT_FOUND);
        }

        return Auth::check() ?
            redirect()->route('profile', Auth::id()) :
            response()->render('auth.register',
                [
                    'data' => [
                        'name'             => null,
                        'email'            => null,
                        'password'         => null,
                        'password_confirm' => null,
                        'referrer_id'      => $referrerUser->getId()
                    ]
                ]);
    }

    /**
     * User registration
     *
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Database\Eloquent\JsonEncodingException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function register(\App\Http\Requests\Auth\RegisterRequest $request)
    {
        $user = (new User)->fill($request->toArray())
            ->referrer()->associate($request->referrer_id);
        $user->save();

        return $request->wantsJson() ?
            response()->render('', ['data' => $user], Response::HTTP_CREATED,
                route('profile', [$user->getId()])) :
            redirect()->route('loginForm');
    }
}
