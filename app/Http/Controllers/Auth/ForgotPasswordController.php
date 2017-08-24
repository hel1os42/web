<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
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
    protected function sendResetLinkFailedResponse(Request $request, string $response): Response
    {
        return response()->render('auth.passwords.email', [
            'message' => trans($response),
        ]);
    }

    /**
     * @param  EmailRequest $request
     * @return Response
     */
    public function sendResetLinkEmail(EmailRequest $request): Response
    {
        $response = $this->broker()->sendResetLink($request->only('email'));
        return $response === Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }
}
