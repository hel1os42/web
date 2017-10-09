<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\FormRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Repositories\UserRepository;
use App\Services\Auth\Otp\OtpAuth;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class RegisterController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

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

        return response()->render('auth.register', $data);
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
                          ->make(['phone' => $phone], ['phone' => 'required|unique:users,phone']);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $otpAuth->generateCode($phone);

        $data = FormRequest::preFilledFormRequest(RegisterRequest::class, [
            'referrer_id' => $referrerUser->id,
            'phone'       => $phone
        ]);

        return $response->render('auth.sms.success',
            $data, Response::HTTP_ACCEPTED, route('register'));
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
        $user = $this->userRepository->create($request->all());

        $success = $user->exists;

        if (!$success) {
            throw new UnprocessableEntityHttpException();
        }

        $user->roles()->attach([Role::findByName(Role::ROLE_USER)->getId()]);

        return request()->wantsJson()
            ? response()->render('', $user, Response::HTTP_CREATED, route('users.show', [$user->getId()]))
            : redirect()->route('loginForm');
    }
}
