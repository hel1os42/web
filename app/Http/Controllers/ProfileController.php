<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{

    /**
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function index()
    {
        return Auth::check() ? redirect()->route('profile', Auth::id()) : response()->render('home', []);
    }

    /**
     * User profile show
     *
     * @param string $uuid
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show(string $uuid)
    {
        $userId = auth()->user()->getId();
        if ($uuid !== $userId) {
            return response()->error(Response::HTTP_FORBIDDEN);
        }
        return response()->render('profile', (new User)->findOrFail($userId)->toArray(), Response::HTTP_CREATED);
    }
}
