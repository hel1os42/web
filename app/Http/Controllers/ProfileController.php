<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\NauModels\User as CoreUser;
use Illuminate\Http\Request;
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
     * @param Request     $request
     * @param string|null $uuid
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \LogicException
     */
    public function show(Request $request, string $uuid = null)
    {
        $userId = auth()->id();

        $with = explode(',', $request->get('with', ''));
        if (count($with) === 1 && $with[0] === '') {
            $with = [];
        }
        if (count($with) !== 0) {
            $with = array_intersect(['accounts', 'offers', 'referrals', 'activationCodes'], $with);
        }

        return (!empty($uuid) && $uuid !== $userId) ?
            response()->error(Response::HTTP_FORBIDDEN) :
            response()->render('profile', (new User)->with($with)->findOrFail($userId)->toArray());
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
