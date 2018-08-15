<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\FormRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\Otp\Exceptions\OtpException;
use App\Services\Auth\Otp\OtpAuth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RegisterController extends AuthController
{

    /**
     * Return user register form
     *
     * @param string $invite
     *
     * @return Response
     * @throws HttpException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function getRegisterForm(string $invite)
    {
        $referrerUser = $this->userRepository->findByInvite($invite);
        if (null === $referrerUser) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Bad user referrer link.');
        }

        $data = FormRequest::preFilledFormRequest(RegisterRequest::class, [
            'referrer_id' => $referrerUser->id
        ]);

        return request()->wantsJson()
            ? response()->render('', $data)
            : redirect()->route('loginForm');
    }

    /**
     * @param string          $invite
     * @param string          $phone
     * @param ResponseFactory $response
     * @param OtpAuth         $otpAuth
     *
     * @return Response
     * @throws HttpException
     * @throws ValidationException
     * @throws \InvalidArgumentException
     */
    public function getOtpCode(string $invite, string $phone, ResponseFactory $response, OtpAuth $otpAuth): Response
    {
        $referrerUser = $this->userRepository->findByInvite($invite);
        if (null === $referrerUser) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Bad user referrer link.');
        }

        $validator = $this->getValidationFactory()
                          ->make(['phone' => $phone], ['phone' => 'required|regex:/\+[0-9]{10,15}/|unique:users,phone']);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $key      = $this->throttleKey(request());
        $attempts = $this->limiter()->attempts($key);

        logger()->debug(
            sprintf(
                '[SMS] Phone - %1$s. URI - %2$s. Key - %3$s. Attempts - %4$d',
                $phone, request()->url(), $key, $attempts
            )
        );

        if ($this->hasTooManyLoginAttempts(\request())) {
            return $this->sendLockoutResponse(\request());
        }

        try {
            $otpAuth->generateCode($phone);
        } catch (OtpException $exception) {
            $message = sprintf('[SMS][Register][Error] %1$s. Phone: %2$s', $exception->getMessage(), $phone);
            logger()->warning($message);

            return $response->error(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }

        $this->incrementLoginAttempts(\request());

        $data = FormRequest::preFilledFormRequest(RegisterRequest::class, [
            'referrer_id' => $referrerUser->id,
            'phone'       => $phone
        ]);

        return $response->render('auth.sms.success',
            $data, Response::HTTP_ACCEPTED, route('register'));
    }
}
