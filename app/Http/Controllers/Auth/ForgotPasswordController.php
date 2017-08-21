<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ForgotPasswordController
 * @package App\Http\Controllers\Auth
 */
class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param  string $response
     * @return Response
     */
    protected function sendResetLinkResponse(string $response): Response
    {
        return response()->render('auth.passwords.email', [
            'message' => trans($response)
        ]);
    }

    /**
     * @param  Request $request
     * @param  string $response
     * @SuppressWarnings("unused")
     * @return Response
     */
    protected function sendResetLinkFailedResponse(Request $request, Response $response): Response
    {
        return response()->render('auth.passwords.email', [
            'message' => trans($response),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function sendResetLinkEmail(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255'
        ]);

        if ($validator->fails()) {
            return response()->render('auth.passwords.email', [
                'errors'   => $validator->errors(),
                'email'    => $request->email,
            ]);
        }

        $response = $this->broker()->sendResetLink($request->only('email'));
        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }
}
