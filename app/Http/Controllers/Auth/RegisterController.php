<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{

    /**
     * User registration
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        if($request->isMethod('post')){

            $user = new User();
            $user->setName($request->name)
            ->setEmail($request->email)
            ->setPassword(Hash::make($request->password))
            ->save();


            if ($request->wantsJson()) {

                return response()->json(['result' => true], 201);
            }
        }
        return redirect()->route('auth/sign_in');
    }

}