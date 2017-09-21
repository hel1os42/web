<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\Auth\Otp\OtpAuth;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

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
        $referrerUser = User::findByInvite($invite);
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
     * @param string          $invite
     * @param string          $phone
     * @param ResponseFactory $response
     *
     * @return Response
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function getOtpCode(string $invite, string $phone, ResponseFactory $response): Response
    {
        $referrerUser = User::findByInvite($invite);
        if (null === $referrerUser) {
            return \response()->error(Response::HTTP_BAD_REQUEST, 'Bad user referrer link.');
        }

        $user = User::findByPhone($phone);

        if ($user instanceof User) {
            throw new BadRequestHttpException('User with phone number ' . $phone . ' already registered.');
        }

        /** @var OtpAuth $otpAuth */
        $otpAuth = app(OtpAuth::class);
        $otpAuth->generateCode($phone);

        return $response->render('auth.sms.success',
            [
                'phone'       => $phone,
                'code'        => null,
                'referrer_id' => $referrerUser->getId()
            ], Response::HTTP_ACCEPTED, route('register'));
    }

    /**
     * User registration
     *
     * @param RegisterRequest $request
     *
     * @return Response
     *
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function register(RegisterRequest $request)
    {
        $user = $request->phone === null
            ? $request->fillUser(new User)
            : $this->registerByPhone($request->phone, $request->code);

        $user->referrer()->associate($request->referrer_id);

        $success = $user->save();

        if (!$success) {
            throw new UnprocessableEntityHttpException();
        }

        $user->refresh();

        return request()->wantsJson()
            ? response()->render('', $user, Response::HTTP_CREATED, route('users.show', [$user->getId()]))
            : redirect()->route('loginForm');
    }

    private function registerByPhone(string $phone, string $code): User
    {
        $user = User::findByPhone($phone);
        if (null !== $user) {
            throw new BadRequestHttpException('User with phone number ' . $phone . ' already registered.');
        }

        /** @var OtpAuth $otpAuth */
        $otpAuth = app(OtpAuth::class);

        if (!$otpAuth->validateCode($phone, $code)) {
            throw new BadRequestHttpException(trans('errors.invalid_code'));
        }

        $user = new User(['phone' => $phone]);

        return $user;
    }
}
