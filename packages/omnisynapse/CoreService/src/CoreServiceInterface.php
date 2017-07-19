<?php

namespace OmniSynapse\CoreService;

// TODO: project models

use App\Models\User;

interface CoreServiceInterface
{
    /**
     * @param XXX $offer
     */
    public function offerCreated(XXX $offer);

    /**
     * @param XXX $redemption
     */
    public function offerRedemption(XXX $redemption);

    /**
     * @param XXX $offer
     */
    public function offerUpdated(XXX $offer);

    /**
     * @param XXX $nau
     */
    public function sendNau(XXX $nau);

    /**
     * @param User $user
     */
    public function userCreated(User $user);
}