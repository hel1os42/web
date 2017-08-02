<?php

namespace OmniSynapse\CoreService;

use App\Models\Offer;
use App\Models\Redemption;
use App\Models\Transact;
use App\Models\User;

interface CoreService
{
    /**
     * @param Offer $offer
     * @return AbstractJob
     */
    public function offerCreated(Offer $offer): AbstractJob;

    /**
     * @param Redemption $redemption
     * @return AbstractJob
     */
    public function offerRedemption(Redemption $redemption): AbstractJob;

    /**
     * @param Offer $offer
     * @return AbstractJob
     */
    public function offerUpdated(Offer $offer): AbstractJob;

    /**
     * @param Transact $transaction
     * @return AbstractJob
     */
    public function sendNau(Transact $transaction): AbstractJob;

    /**
     * @param User $user
     * @return AbstractJob
     */
    public function userCreated(User $user): AbstractJob;

    /**
     * @param Transact $transaction
     * @param string $category
     * @return AbstractJob
     */
    public function transactionNotification(Transact $transaction, $category): AbstractJob;
}
