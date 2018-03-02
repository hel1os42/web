<?php

namespace App\Http\Controllers;

use App\Repositories\ActivationCodeRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

/**
 * Class ActivationCodeController
 * NS: App\Http\Controllers
 */
class ActivationCodeController extends Controller
{
    private $activationCodeRepository;

    public function __construct(ActivationCodeRepository $activationCodeRepository, AuthManager $auth)
    {
        $this->activationCodeRepository = $activationCodeRepository;

        parent::__construct($auth);
    }

    public function show(Request $request, $code)
    {
        $activationCode = $this->activationCodeRepository
            ->findByCode($code);

        if (null === $activationCode) {
            throw (new ModelNotFoundException)->setModel($this->activationCodeRepository->model());
        }

        $this->authorize('activation_codes.show', $activationCode);

        if (in_array('offer', explode(',', $request->get('with', '')))) {
            $activationCode->append('offer');
        }

        return response()->render('activation_code.show', $activationCode->toArray());
    }
}
