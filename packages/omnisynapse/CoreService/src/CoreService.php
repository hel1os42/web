<?php

namespace OmniSynapse\CoreService;

use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use App\Models\NauModels\Transact;
use App\Models\User;
use GuzzleHttp\Client;

interface CoreService
{
    /**
     * @param Client $client
     * @return CoreService
     */
    public function setClient(Client $client): CoreService;

    /**
     * @return \GuzzleHttp\Client
     */
    public function getClient(): Client;

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

    /**
     * @param Offer $offer
     *
     * @return AbstractJob
     */
    public function offerDeleted(Offer $offer): AbstractJob;
}
