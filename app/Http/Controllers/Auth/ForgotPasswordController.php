<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\EmailRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ForgotPasswordController
 * @package App\Http\Controllers\Auth
 */
class ForgotPasswordController extends AuthController
{
    use SendsPasswordResetEmails;

    /**
     * @param  string $response
     * @return Response
     */
    protected function sendResetLinkResponse(string $response): Response
    {
        return response()->render('auth.passwords.email.sended', [
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
        try{
            $response = $this->broker()->sendResetLink($request->only('email'));
        } catch (\Swift_TransportException $exception) {
            $response = 'passwords.sentError';
            logger("Email reset password error!!! Error" . $exception->getMessage());
        }
        return $response === Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }
}
