<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\NauModels\User as CoreUser;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{

    /**
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function index()
    {
        return Auth::check() ? redirect()->route('profile') : response()->render('home', []);
    }

    /**
     * User profile show
     *
     * @param string $uuid
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show(string $uuid = null)
    {
        $userId = auth()->id();
        return (!empty($uuid) && $uuid !== $userId) ?
            response()->error(Response::HTTP_FORBIDDEN) :
            response()->render('profile', (new User)->findOrFail($userId)->toArray());
    }

    /**
     * @param string|null $uuid
     *
     * @return Response
     */
    public function referrals(string $uuid = null)
    {
        $userId = auth()->id();
        return ($uuid === null || $uuid === $userId) ?
            response()->render('user.profile.referrals', (new User)->findOrFail($userId)->referrals()->paginate()) :
            response()->error(Response::HTTP_FORBIDDEN);
    }


}
