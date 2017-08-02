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
    /** @var Client $client */
    private $client;

    /** @var array $config */
    private $config;

    /**
     * CoreServiceImpl constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param Client $client
     *
     * @return CoreService
     */
    public function setClient(Client $client): CoreService
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getClient(): Client
    {
        if (null === $this->client) {
            $this->client = $this->initClient();
        }

        return $this->client;
    }

    /**
     * @return Client
     */
    private function initClient()
    {
        return new Client($this->config);
    }

    /**
     * @param Offer $offer
     * @return AbstractJob
     */
    public function offerCreated(Offer $offer): AbstractJob
    {
        return new OfferCreated($offer, $this->getClient());
    }

    /**
     * @param Redemption $redemption
     * @return AbstractJob
     */
    public function offerRedemption(Redemption $redemption): AbstractJob
    {
        return new OfferRedemption($redemption, $this->getClient());
    }

    /**
     * @param Offer $offer
     * @return AbstractJob
     */
    public function offerUpdated(Offer $offer): AbstractJob
    {
        return new OfferUpdated($offer, $this->getClient());
    }

    /**
     * @param Transact $transaction
     * @return AbstractJob
     */
    public function sendNau(Transact $transaction): AbstractJob
    {
        return new SendNau($transaction, $this->getClient());
    }

    /**
     * @param User $user
     * @return AbstractJob
     */
    public function userCreated(User $user): AbstractJob
    {
        return new UserCreated($user, $this->getClient());
    }

    /**
     * @param Transact $transaction
     * @param string   $category
     *
     * @return AbstractJob
     */
    public function transactionNotification(Transact $transaction, $category): AbstractJob
    {
        return new TransactionNotification($transaction, $category, $this->getClient());
    }
}
