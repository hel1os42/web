<?php

namespace OmniSynapse\CoreService;

use App\Models\Offer;
use App\Models\Redemption;
use App\Models\Transact;
use App\Models\User;
use OmniSynapse\CoreService\Job\OfferCreated;
use OmniSynapse\CoreService\Job\OfferRedemption;
use OmniSynapse\CoreService\Job\OfferUpdated;
use OmniSynapse\CoreService\Job\SendNau;
use OmniSynapse\CoreService\Job\TransactionNotification;
use OmniSynapse\CoreService\Job\UserCreated;

class CoreServiceImpl implements CoreServiceInterface
{
    /** @var \GuzzleHttp\Client $client */
    private $client;

    /**
     * CoreService constructor.
     *
     * @param \GuzzleHttp\Client|null $client
     */
    public function __construct(\GuzzleHttp\Client $client=null)
    {
        $this->client = $client;
    }

    /**
     * @param Offer $offer
     * @return Job
     */
    public function offerCreated(Offer $offer) : Job
    {
        return new OfferCreated($offer, $this->client);
    }

    /**
     * @param Redemption $redemption
     * @return Job
     */
    public function offerRedemption(Redemption $redemption) : Job
    {
        return new OfferRedemption($redemption, $this->client);
    }

    /**
     * @param Offer $offer
     * @return Job
     */
    public function offerUpdated(Offer $offer) : Job
    {
        return new OfferUpdated($offer, $this->client);
    }

    /**
     * @param Transact $transaction
     * @return Job
     */
    public function sendNau(Transact $transaction) : Job
    {
        return new SendNau($transaction, $this->client);
    }

    /**
     * @param User $user
     * @return Job
     */
    public function userCreated(User $user) : Job
    {
        return new UserCreated($user, $this->client);
    }

    /**
     * @param Transact $transaction
     * @param string $category
     * @return Job
     */
    public function transactionNotification(Transact $transaction, $category) : Job
    {
        return new TransactionNotification($transaction, $category, $this->client);
    }
}
