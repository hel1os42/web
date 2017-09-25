<?php

namespace App\Http\Controllers;

use App\Models\ActivationCode;

/**
 * Class ActivationCodeController
 * NS: App\Http\Controllers
 */
class ActivationCodeController extends Controller
{
    use HandlesRequestData;

    public function show($code)
    {
        $with = $this->handleWith(['user', 'offer', 'redemption'], request());

        $activationCode = ActivationCode::byCode($code)->byOwner(auth()->user())
                                        ->with($with)->firstOrFail();

        return response()->render('activation_code.show', $activationCode->toArray());
    }
}
