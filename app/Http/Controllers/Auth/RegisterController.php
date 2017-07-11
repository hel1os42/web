<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{

    public function getRegister()
    {
        return response()->render('auth.register');
    }

    /**
     * User registration
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function postRegister(Request $request)
    {
        // todo @mobixon Auth token + Core request

        $user = new User();
        $user->setName($request->name)
            ->setEmail($request->email)
            ->setPassword(Hash::make($request->password));
        $user->save();


        if ($request->wantsJson()) {
            return response()->render(null, ['user' => User::find($user->id)], 201);
        }

        return redirect()->route('profile');


    }

}