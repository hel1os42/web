<?php

namespace App\Services;

use App\Exceptions\Offer\Redemption\BadActivationCodeException;
use App\Exceptions\Offer\Redemption\CannotRedeemException;
use App\Models\ActivationCode;
use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use App\Models\User;
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
    public function redeemByOfferAndCode(Offer $offer, string $code): Redemption
    {
        $activationCode = $this->activationCodeRepository
            ->findByCodeAndOfferAndNotRedeemed($code, $offer);

        if (null === $activationCode) {
            throw new BadActivationCodeException($offer, $code);
        }

        return $this->redeem($activationCode);
    }

    public function redeemByOwnerAndCode(User $owner, string $code)
    {
        $activationCode = $this->activationCodeRepository
            ->findByCodeAndNotRedeemed($code);

        if (null === $activationCode) {
            throw new BadActivationCodeException(null, $code);
        }

        $offer = $activationCode->offer;
        if (null === $offer || !$offer->isOwner($owner)) {
            throw new BadActivationCodeException($offer, $code);
        }

        return $this->redeem($activationCode);
    }

    /**
     * @param ActivationCode $activationCode
     *
     * @return Redemption
     * @throws CannotRedeemException
     * @throws \Illuminate\Database\Eloquent\JsonEncodingException
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    private function redeem(?ActivationCode $activationCode): Redemption
    {
        /** @var Redemption $redemption */
        $redemption = $activationCode->offer->redemptions()->create([
            'user_id' => $activationCode->getUserId()
        ]);

        if (null === $redemption->id) {
            throw new CannotRedeemException($activationCode->offer, $activationCode->getCode());
        }

        $activationCode->redemption()->associate($redemption)->update();

        return $redemption;
    }
}
