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
     * @return \Illuminate\Http\JsonResponse|Response|\Illuminate\Http\RedirectResponse
     */
    public function getRegisterForm(string $invite)
    {
        $referrerUser = new User();
        $referrerUser = $referrerUser->findByInvite($invite);
        if(!$referrerUser instanceof User){
            return response()->error(Response::HTTP_NOT_FOUND);
        }

        return Auth::check() ?
            redirect()->route('profile', Auth::id()) :
            response()->render('auth.register', [
                'referrer_id' => $referrerUser->getId(),
                'login' => null,
                'password' => null,
                'password_confirm' => null
            ]);
    }

    /**
     * User registration
     *
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function register(\App\Http\Requests\Auth\RegisterRequest $request)
    {
        $user = new User();
        $user->setName($request->name)
            ->setEmail($request->email)
            ->setPassword($request->password);
        $user->setInvite($user->generateInvite());
        $user->referrer()->associate($request->referrer_id);
        $user->save();

        if ($request->wantsJson()) {
            return response()
                ->render('', $user->fresh(), Response::HTTP_CREATED)
                ->header('Location', sprintf('/users/%s', $user->id));
        }

        return redirect()->route('loginForm');

    }

}