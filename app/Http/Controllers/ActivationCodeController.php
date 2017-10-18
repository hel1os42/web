<?php

namespace App\Http\Controllers;

use App\Repositories\ActivationCodeRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ActivationCodeController
 * NS: App\Http\Controllers
 */
class ActivationCodeController extends Controller
{
    private $activationCodeRepository;
    private $auth;

    public function __construct(ActivationCodeRepository $activationCodeRepository, AuthManager $auth)
    {
        $this->activationCodeRepository = $activationCodeRepository;
        $this->auth                     = $auth;
    }

    public function show($code)
    {
        $activationCode = $this->activationCodeRepository
            ->findByCodeAndUser($code, $this->auth->guard()->user());

        if (null === $activationCode) {
            throw (new ModelNotFoundException)->setModel($this->activationCodeRepository->model());
        }

        $this->authorize('show', $activationCode);

        return response()->render('activation_code.show', $activationCode->toArray());
    }
}
