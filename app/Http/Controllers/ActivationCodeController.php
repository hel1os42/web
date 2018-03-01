<?php

namespace App\Http\Controllers;

use App\Models\ActivationCode;
use App\Repositories\ActivationCodeRepository;
use App\Repositories\OfferRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function show($code)
    {
        if (config('app.review_stub.code') === $code) {
            return $this->reviewStub();
        }

        $activationCode = $this->activationCodeRepository
            ->findByCodeAndUser($code, $this->user());

        if (null === $activationCode) {
            throw (new ModelNotFoundException)->setModel($this->activationCodeRepository->model());
        }

        $this->authorize('activation_codes.show', $activationCode);

        return response()->render('activation_code.show', $activationCode->toArray());
    }

    private function reviewStub()
    {
        $offer          = app(OfferRepository::class)->skipCriteria()->findWithoutGlobalScopes(config('app.review_stub.offer_id'));
        $activationCode = (new ActivationCode())->forceFill([
            'id'            => 115,
            'user_id'       => config('app.review_stub.user_id'),
            'offer_id'      => config('app.review_stub.offer_id'),
            'redemption_id' => null,
            'created_at'    => '2018-02-25 12:33:58',
            'updated_at'    => '2018-02-25 12:33:58',
            'offer'         => $offer,
        ]);

        $activationCodeArray         = $activationCode->toArray();
        $activationCodeArray['code'] = config('app.review_stub.code');

        return response()->render('activation_code.show', $activationCodeArray);
    }
}
