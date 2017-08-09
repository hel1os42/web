<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Transact;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\Request\TransactionNotification as TransactionNotificationRequest;
use OmniSynapse\CoreService\Response\Transaction;

class TransactionNotification extends AbstractJob
{
    /** @var TransactionNotificationRequest */
    private $requestObject;

    /**
     * TransactionNotification constructor.
     *
     * @param Transact $transaction
     * @param string $category
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Transact $transaction, $category, \GuzzleHttp\Client $client)
    {
        parent::__construct($client);

        /** @var SendNau requestObject */
        $this->requestObject = (new TransactionNotificationRequest($transaction, $category));
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * @return string
     */
    public function getHttpPath(): string
    {
        return '/transactions/incoming';
    }

    /**
     * @return \JsonSerializable
     */
    public function getRequestObject(): \JsonSerializable
    {
        return $this->requestObject;
    }

    /**
     * @return string
     */
    public function getResponseClass(): string
    {
        return Transaction::class;
    }
}
