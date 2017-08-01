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
     * @param Client $client
     * @return CoreServiceImpl
     */
    public function setClient(Client $client) : CoreService;

    /**
     * @return Client
     */
    public function getClient() : Client;

    /**
     * @param Offer $offer
     * @return Job
     */
    public function offerCreated(Offer $offer) : Job;

    /**
     * @param Redemption $redemption
     * @return Job
     */
    public function offerRedemption(Redemption $redemption) : Job;

    /**
     * @param Offer $offer
     * @return Job
     */
    public function offerUpdated(Offer $offer) : Job;

    /**
     * @param Transact $transaction
     * @return Job
     */
    public function sendNau(Transact $transaction) : Job;

    /**
     * @param User $user
     * @return Job
     */
    public function userCreated(User $user) : Job;

    /**
     * @param Transact $transaction
     * @param string $category
     * @return Job
     */
    public function transactionNotification(Transact $transaction, $category) : Job;
}
