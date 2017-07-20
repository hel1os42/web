<?php

namespace OmniSynapse\CoreService;

// TODO: project models

use App\Models\User;

interface CoreServiceInterface
{
    /**
     * @param XXX $offer
     * @return Job
     */
    public function offerCreated(XXX $offer) : Job;

    /**
     * @param XXX $redemption
     * @return Job
     */
    public function offerRedemption(XXX $redemption) : Job;

    /**
     * @param XXX $offer
     * @return Job
     */
    public function offerUpdated(XXX $offer) : Job;

    /**
     * @param XXX $nau
     * @return Job
     */
    public function sendNau(XXX $nau) : Job;

    /**
     * @param User $user
     * @return Job
     */
    public function userCreated(User $user) : Job;
}