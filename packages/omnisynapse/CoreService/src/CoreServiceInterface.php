<?php

namespace OmniSynapse\CoreService;

use OmniSynapse\CoreService\Entity\Nau;
use OmniSynapse\CoreService\Entity\Offer;
use OmniSynapse\CoreService\Entity\Redemption;
use OmniSynapse\CoreService\Entity\User;

interface CoreServiceInterface
{
    /**
     * @param Offer $offer
     */
    public function offerCreated(Offer $offer);

    /**
     * @param Redemption $redemption
     */
    public function offerRedemption(Redemption $redemption);

    /**
     * @param Offer $offer
     */
    public function offerUpdated(Offer $offer);

    /**
     * @param Nau $nau
     */
    public function sendNau(Nau $nau);

    /**
     * @param User $user
     * @param User|null $referrer
     */
    public function userCreated(User $user, User $referrer = null);
}