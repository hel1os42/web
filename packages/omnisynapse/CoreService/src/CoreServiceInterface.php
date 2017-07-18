<?php

namespace OmniSynapse\CoreService;

use App\Models\Nau;
use App\Models\Offer;
use App\Models\User;

interface CoreServiceInterface
{
    /**
     * @param Offer $offer
     */
    public function offerCreated(Offer $offer);

    /**
     * @param Offer $redemption
     */
    public function offerRedemption(Offer $redemption);

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
     */
    public function userCreated(User $user);
}