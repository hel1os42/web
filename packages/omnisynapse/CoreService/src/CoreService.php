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
     * @return Job
     */
    public function offerCreated(XXX $offer) : Job
    {
        return new OfferCreated($offer);
    }

    /**
     * @param XXX $redemption
     * @return Job
     */
    public function offerRedemption(XXX $redemption) : Job
    {
        return new OfferRedemption($redemption);
    }

    /**
     * @param XXX $offer
     * @return Job
     */
    public function offerUpdated(XXX $offer) : Job
    {
        return new OfferUpdated($offer);
    }

    /**
     * @param XXX $nau
     * @return Job
     */
    public function sendNau(XXX $nau) : Job
    {
        return new SendNau($nau);
    }

    /**
     * @param User $user
     * @return Job
     */
    public function userCreated(User $user) : Job
    {
        return new UserCreated($user);
    }
}