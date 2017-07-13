<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{

    public function index()
    {
        return Auth::check() ? redirect()->route('profile', Auth::id()) : response()->render('home');
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
        $userId = Auth::id();
        if ($id !== $userId) {
            abort(404);
        }
        return response()->render('user.profile', User::find($userId)->fresh(), 201);
    }

}