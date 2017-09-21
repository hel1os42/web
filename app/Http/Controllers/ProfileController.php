<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
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
     * @param Request $request
     * @param string|null $uuid
     *
     * @return Response
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \LogicException
     */
    public function show(Request $request, string $uuid = null): Response
    {
        $userId = auth()->id();

        $with = explode(',', $request->get('with', ''));
        $with = array_intersect(['accounts', 'offers', 'referrals', 'activationCodes'], $with);

        return (!empty($uuid) && $uuid !== $userId) ?
            response()->error(Response::HTTP_FORBIDDEN) :
            response()->render('profile', (new User)->with($with)->findOrFail($userId)->toArray());
    }

    /**
     * @param ProfileUpdateRequest $request
     * @param string|null $uuid
     * @return Response
     */
    public function update(ProfileUpdateRequest $request, string $uuid = null): Response
    {
        $user = auth()->user();
        if (!is_null($uuid) && auth()->id() != $uuid) {
            return \response()->error(Response::HTTP_UNAUTHORIZED);
        }

        $success = request()->isMethod('put') ?
            $user->update(array_merge((new User)->toArray(), $request->except('password'))) :
            $user->update($request->except('password'));

        if ($success) {
            return \response()->render('profile', User::findOrFail(auth()->id()), Response::HTTP_CREATED, route('profile'));
        }
        return \response()->error(Response::HTTP_NOT_ACCEPTABLE);
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
