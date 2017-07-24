<?php

namespace OmniSynapse\CoreService;

use App\Models\Offer;
use App\Models\Redemption;
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
     * @return Job
     */
    public function offerCreated(Offer $offer) : Job
    {
        return new OfferCreated($offer);
    }

    /**
     * @param Redemption $redemption
     * @return Job
     */
    public function offerRedemption(Redemption $redemption) : Job
    {
        return new OfferRedemption($redemption);
    }

    /**
     * @param Offer $offer
     * @return Job
     */
    public function offerUpdated(Offer $offer) : Job
    {
        return new OfferUpdated($offer);
    }

    /**
     * @return Job
     */
    public function sendNau() : Job
    {
        return new SendNau();
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