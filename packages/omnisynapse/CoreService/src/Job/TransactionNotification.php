<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\Transact;
use OmniSynapse\CoreService\CoreServiceClient;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\TransactionNotification as TransactionNotificationRequest;
use OmniSynapse\CoreService\Response\Transaction;

class TransactionNotification extends Job
{
    /**
     * TransactionNotification constructor.
     *
     * @param Transact $transaction
     * @param string $category
     * @param \GuzzleHttp\Client|null $client
     */
    public function __construct(Transact $transaction, $category, \GuzzleHttp\Client $client=null)
    {
        $this->guzzleClient = $client;

        /** @var SendNau requestObject */
        $this->requestObject = (new TransactionNotificationRequest($transaction, $category));
    }

    /**
     * @return string
     */
    public function getHttpMethod() : string
    {
        return CoreServiceClient::METHOD_POST;
    }

    /**
     * @return string
     */
    public function getHttpPath() : string
    {
        return '/transactions/incoming';
    }

    /**
     * @return \JsonSerializable
     */
    protected function getRequestObject() : \JsonSerializable
    {
        return $this->requestObject;
    }

    /**
     * @return string
     */
    protected function getResponseClass() : string
    {
        return Transaction::class;
    }
}
