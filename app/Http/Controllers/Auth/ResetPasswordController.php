<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordFormRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Auth\CanResetPassword;

/**
 * Class ResetPasswordController
 * @package App\Http\Controllers\Auth
 */
class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param  Request $request
     * @param  string|null $token
     * @return Response
     */
    public function showResetForm(Request $request, string $token = null): Response
    {
        return response()->render('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * @param string $response
     * @return Response
     */
    protected function sendResetResponse(string $response): Response
    {
        return response()->render('auth.login', [
            'errors' => trans($response),
        ]);
    }

    /**
     * @param  Request $request
     * @param  string $response
     * @SuppressWarnings("unused")
     * @return Response
     */
    protected function sendResetFailedResponse(Request $request, string $response): Response
    {
        return response()->render('auth.passwords.reset', [
            'email'  => $request->email,
            'errors' => trans($response),
        ]);
    }

    /**
     * @param  PasswordFormRequest $request
     * @return Response
     */
    public function reset(PasswordFormRequest $request)
    {
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($response)
            : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * @param  CanResetPassword $user
     * @param  string $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->fill([
            'password' => $password,
            'remember_token' => Str::random(60),
        ])->save();
    }
}
