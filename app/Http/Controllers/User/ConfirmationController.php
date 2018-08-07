<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\ConfirmationService;
use Illuminate\Http\Response;

/**
 * Class ConfirmationController
 *
 * @package App\Http\Controllers
 */
class ConfirmationController extends Controller
{
    /**
     * @param string $token
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function index($token)
    {
        if (false === app(ConfirmationService::class)->confirm($token)) {
            return \response()->error(Response::HTTP_NOT_FOUND);
        }

        $message = trans('mails.user.confirm.success');

        if (request()->wantsJson()) {
            return \response()->json(['message' => $message]);
        }

        return \response()->redirectTo(route('loginForm'))->with('success', $message);
    }
}
