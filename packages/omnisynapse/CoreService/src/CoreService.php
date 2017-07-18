<?php

namespace OmniSynapse\CoreService;

use App\Models\Nau;
use App\Models\Offer;
use App\Models\User;
use OmniSynapse\CoreService\Job\OfferCreated;
use OmniSynapse\CoreService\Job\OfferRedemption;
use OmniSynapse\CoreService\Job\OfferUpdated;
use OmniSynapse\CoreService\Job\SendNau;
use OmniSynapse\CoreService\Job\UserCreated;

class CoreService implements CoreServiceInterface
{
    /** @var array */
    protected $config;

    /**
     * CoreService constructor.
     * @param array|null $config
     */
    public function __construct($config = null)
    {
        $this->config = $config;
    }

    /**
     * @param Offer $offer
     * @return OfferCreated
     */
    public function offerCreated(Offer $offer) : OfferCreated
    {
        return new OfferCreated($offer);
    }

    /**
     * @param Offer $redemption
     * @return OfferRedemption
     */
    public function offerRedemption(Offer $redemption) : OfferRedemption
    {
        return new OfferRedemption($redemption);
    }

    /**
     * @param Offer $offer
     * @return OfferUpdated
     */
    public function offerUpdated(Offer $offer) : OfferUpdated
    {
        return new OfferUpdated($offer);
    }

    /**
     * @param Nau $nau
     * @return SendNau
     */
    public function sendNau(Nau $nau) : SendNau
    {
        return new SendNau($nau);
    }

    /**
     * @param User $user
     * @return UserCreated
     */
    public function userCreated(User $user) : UserCreated
    {
        return new UserCreated($user);
    }
}