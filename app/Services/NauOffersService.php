<?php

namespace App\Services;

use App\Exceptions\Offer\Redemption\BadActivationCodeException;
use App\Exceptions\Offer\Redemption\CannotRedeemException;
use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use App\Repositories\ActivationCodeRepository;
use App\Repositories\OfferRepository;

/**
 * Class NauOffersService
 * NS: App\Services
 */
class NauOffersService implements OffersService
{
    private $activationCodeRepository;
    private $offerRepository;

    public function __construct(ActivationCodeRepository $activationCodeRepository, OfferRepository $offerRepository)
    {
        $this->activationCodeRepository = $activationCodeRepository;
        $this->offerRepository          = $offerRepository;
    }

    /**
     * @param Offer  $offer
     * @param string $code
     *
     * @return Redemption
     * @throws BadActivationCodeException
     * @throws CannotRedeemException
     * @throws \Illuminate\Database\Eloquent\JsonEncodingException
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function redeem(Offer $offer, string $code): Redemption
    {
        $activationCode = $this->activationCodeRepository
            ->findByCodeAndOfferAndNotRedeemed($code, $offer);

        if (null === $activationCode) {
            throw new BadActivationCodeException($offer, $code);
        }

        /** @var Redemption $redemption */
        $redemption = $offer->redemptions()->create([
            'user_id' => $activationCode->getUserId()
        ]);

        if (null === $redemption->id) {
            throw new CannotRedeemException($offer, $activationCode->getCode());
        }

        $activationCode->redemption()->associate($redemption)->update();

        return $redemption;
    }
}
