<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    /**
     * Return user register form
     *
     * @param string $invite
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function getRegisterForm(string $invite)
    {
        $referrerUser = (new User())->findByInvite($invite);
        if (!$referrerUser instanceof User) {
            return response()->error(Response::HTTP_BAD_REQUEST, 'Bad user referrer link.');
        }

        return Auth::check() ?
            response()->error(Response::HTTP_BAD_REQUEST, 'User already login.') :
            response()->render('auth.register',
                [
                    'email'            => null,
                    'password'         => null,
                    'password_confirm' => null,
                    'referrer_id'      => $referrerUser->getId()
                ]);
    }

    /**
     * @param string $invite
     * @param string $phone
     * @return Response
     */
    public function sendSmsCode(string $invite, string $phone): Response
    {
        $referrerUser = (new User())->findByInvite($invite);
        if (!$referrerUser instanceof User) {
            return \response()->error(Response::HTTP_BAD_REQUEST, 'Bad user referrer link.');
        }

        $user = User::findByPhone($phone);

        if ($user instanceof User) {
            return \response()->error(Response::HTTP_BAD_REQUEST,
                'User with phone number ' . $phone . ' already registered.');
        }

        cache()->put($phone, app(\App\Helpers\SmsAuth::class)->getCode($phone), 5);

        return Auth::check() ?
            \response()->error(Response::HTTP_BAD_REQUEST, 'User already login.') :
            \response()->render('auth.sms.success',
                [
                    'phone_number' => $phone,
                    'code'         => null,
                    'referrer_id'  => $referrerUser->getId()
                ], Response::HTTP_ACCEPTED, route('register'));
    }

    /**
     * User registration
     *
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Database\Eloquent\JsonEncodingException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function register(\App\Http\Requests\Auth\RegisterRequest $request)
    {
        if ($request->phone !== null) {
            $code = cache()->get($request->phone);

            $newCode = $request->code;

            if ($code === null || $newCode !== $code) {
                return response()->error(Response::HTTP_BAD_REQUEST, trans('errors.invalid_code'));
            }
        }

        $user = (new User)->fill($request->toArray())
            ->referrer()->associate($request->referrer_id);
        $user->save();

        return $request->wantsJson() ?
            response()->render('', $user, Response::HTTP_CREATED,
                route('users.show', [$user->getId()])) :
            redirect()->route('loginForm');
    }
}
