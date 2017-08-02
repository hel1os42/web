<?php

namespace OmniSynapse\CoreService;

use App\Models\Offer;
use App\Models\Redemption;
use App\Models\Transact;
use App\Models\User;
use GuzzleHttp\Client;
use OmniSynapse\CoreService\Job\OfferCreated;
use OmniSynapse\CoreService\Job\OfferRedemption;
use OmniSynapse\CoreService\Job\OfferUpdated;
use OmniSynapse\CoreService\Job\SendNau;
use OmniSynapse\CoreService\Job\TransactionNotification;
use OmniSynapse\CoreService\Job\UserCreated;

class CoreServiceImpl implements CoreService
{
    /**
     * @param Offer $offer
     * @param Client $client
     * @return AbstractJob
     */
    public function offerCreated(Offer $offer, Client $client): AbstractJob
    {
        return new OfferCreated($offer, $client);
    }

    /**
     * @param Redemption $redemption
     * @param Client $client
     * @return AbstractJob
     */
    public function offerRedemption(Redemption $redemption, Client $client): AbstractJob
    {
        return new OfferRedemption($redemption, $client);
    }

    /**
     * @param Offer $offer
     * @param Client $client
     * @return AbstractJob
     */
    public function offerUpdated(Offer $offer, Client $client): AbstractJob
    {
        return new OfferUpdated($offer, $client);
    }

    /**
     * @param Transact $transaction
     * @param Client $client
     * @return AbstractJob
     */
    public function sendNau(Transact $transaction, Client $client): AbstractJob
    {
        return new SendNau($transaction, $client);
    }

    /**
     * @param User $user
     * @param Client $client
     * @return AbstractJob
     */
    public function userCreated(User $user, Client $client): AbstractJob
    {
        return new UserCreated($user, $client);
    }

    /**
     * @param Transact $transaction
     * @param string   $category
     * @param Client $client
     * @return AbstractJob
     */
    public function transactionNotification(Transact $transaction, $category, Client $client): AbstractJob
    {
        return new TransactionNotification($transaction, $category, $client);
    }
}
