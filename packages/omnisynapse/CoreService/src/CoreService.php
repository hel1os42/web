<?php

namespace OmniSynapse\CoreService;

// TODO: project models

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
     * @param XXX $offer
     * @return OfferCreated
     */
    public function offerCreated(XXX $offer) : OfferCreated
    {
        return new OfferCreated($offer);
    }

    /**
     * @param XXX $redemption
     * @return OfferRedemption
     */
    public function offerRedemption(XXX $redemption) : OfferRedemption
    {
        return new OfferRedemption($redemption);
    }

    /**
     * @param XXX $offer
     * @return OfferUpdated
     */
    public function offerUpdated(XXX $offer) : OfferUpdated
    {
        return new OfferUpdated($offer);
    }

    /**
     * @param XXX $nau
     * @return SendNau
     */
    public function sendNau(XXX $nau) : SendNau
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