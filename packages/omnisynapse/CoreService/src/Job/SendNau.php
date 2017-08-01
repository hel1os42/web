<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\Transact;
use OmniSynapse\CoreService\CoreServiceClient;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Response\Transaction;
use OmniSynapse\CoreService\Request\SendNau as SendNauRequest;

/**
 * Class SendNau
 * @package OmniSynapse\CoreService\Job
 */
class SendNau extends Job
{
    /**
     * SendNau constructor.
     *
     * @param Transact $transaction
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Transact $transaction, \GuzzleHttp\Client $client=null)
    {
        $this->guzzleClient = $client;

        /** @var SendNau requestObject */
        $this->requestObject = (new SendNauRequest($transaction));
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
        return '/transactions';
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
