<?php

namespace OmniSynapse\CoreService;

use OmniSynapse\CoreService\Entity\Nau;
use OmniSynapse\CoreService\Entity\Offer;
use OmniSynapse\CoreService\Entity\Redemption;
use OmniSynapse\CoreService\Entity\User;
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
     * @param Redemption $redemption
     * @return OfferRedemption
     */
    public function offerRedemption(Redemption $redemption) : OfferRedemption
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
     * @param User|null $referrer
     * @return UserCreated
     */
    public function userCreated(User $user, User $referrer = null) : UserCreated
    {
        return new UserCreated($user, $referrer);
    }
}