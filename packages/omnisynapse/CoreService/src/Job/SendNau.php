<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\Transact;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\Response\Transaction;
use OmniSynapse\CoreService\Request\SendNau as SendNauRequest;

/**
 * Class SendNau
 * @package OmniSynapse\CoreService\Job
 */
class SendNau extends AbstractJob
{
    /** @var SendNauRequest */
    private $requestObject;

    /**
     * SendNau constructor.
     *
     * @param Transact $transaction
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Transact $transaction, \GuzzleHttp\Client $client)
    {
        parent::__construct($client);

        /** @var SendNau requestObject */
        $this->requestObject = (new SendNauRequest($transaction));
    }

    /**
     * @return string
     */
    protected function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * @return string
     */
    protected function getHttpPath(): string
    {
        return '/transactions';
    }

    /**
     * @return \JsonSerializable
     */
    protected function getRequestObject(): \JsonSerializable
    {
        return $this->requestObject;
    }

    /**
     * @return string
     */
    protected function getResponseClass(): string
    {
        return Transaction::class;
    }
}
