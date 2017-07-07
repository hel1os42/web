<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Users;
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
            User::create([
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'referrer_id' => $request->referrer_id
            ]);

            if ($request->wantsJson()) {

                return response()->json(['result' => true], 201);
            }
        }
        return redirect()->route('auth/sign_in');
    }

}