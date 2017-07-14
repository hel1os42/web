<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class RegisterController extends Controller
{

    /**
     * Return user register form
     *
     * @return mixed
     */
    public function getRegister()
    {
        return Auth::check() ? redirect()->route('profile', Auth::id()) : response()->render('auth.register');
    }

    /**
     * User registration
     *
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function postRegister(\App\Http\Requests\Auth\RegisterRequest $request)
    {
        $user = new User();
        $user->setName($request->name)
            ->setEmail($request->email)
            ->setPassword(Hash::make($request->password));
        $user->save();

        if ($request->wantsJson()) {
            return response()
                ->render(null, $user->fresh(), 201)
                ->header('Location', sprintf('/users/%s', $user->id));
        }

        return redirect()->route('login');

    }

}