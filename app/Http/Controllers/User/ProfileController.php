<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class ProfileController extends Controller
{

    public function index()
    {
        return Auth::check() ? redirect()->route('profile', Auth::id()) : response()->render('home');
    }

    /**
     * User profile show
     *
     * @param string $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $userId = Auth::id();
        if ($id !== $userId) {
            return response()->error(Response::HTTP_FORBIDDEN);
        }
        return response()->render('user.profile', User::find($userId)->fresh(), Response::HTTP_CREATED);
    }

}