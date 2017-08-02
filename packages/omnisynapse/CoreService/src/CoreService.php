<?php

namespace OmniSynapse\CoreService;

use App\Models\Offer;
use App\Models\Redemption;
use App\Models\Transact;
use App\Models\User;
use GuzzleHttp\Client;

interface CoreService
{
    /**
     * @param Offer $offer
     * @param Client $client
     * @return AbstractJob
     */
    public function offerCreated(Offer $offer, Client $client): AbstractJob;

    /**
     * @param Redemption $redemption
     * @param Client $client
     * @return AbstractJob
     */
    public function offerRedemption(Redemption $redemption, Client $client): AbstractJob;

    /**
     * @param Offer $offer
     * @param Client $client
     * @return AbstractJob
     */
    public function offerUpdated(Offer $offer, Client $client): AbstractJob;

    /**
     * @param Transact $transaction
     * @param Client $client
     * @return AbstractJob
     */
    public function sendNau(Transact $transaction, Client $client): AbstractJob;

    /**
     * @param User $user
     * @param Client $client
     * @return AbstractJob
     */
    public function userCreated(User $user, Client $client): AbstractJob;

    /**
     * @param Transact $transaction
     * @param string $category
     * @param Client $client
     * @return AbstractJob
     */
    public function transactionNotification(Transact $transaction, $category, Client $client): AbstractJob;
}
