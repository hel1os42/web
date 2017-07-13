<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{

    public function index()
    {
        return redirect()->route('profile', Auth::id());
    }

    /**
     * User profile show
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return response()->render('user.profile', ['user' => User::find($id)], 201);
    }

}