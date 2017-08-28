<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Transact;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\Response\Transaction;
use OmniSynapse\CoreService\Request\SendNau as SendNauRequest;
use OmniSynapse\CoreServise\Failed\Failed;

/**
 * Class SendNau
 * @package OmniSynapse\CoreService\Job
 */
class SendNau extends AbstractJob
{
    /** @var SendNauRequest */
    private $requestObject;

    /** @var Transact */
    private $transaction;

    /**
     * SendNau constructor.
     *
     * @param Transact $transaction
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Transact $transaction, \GuzzleHttp\Client $client)
    {
        parent::__construct($client);

        $this->transaction = $transaction;

        /** @var SendNau requestObject */
        $this->requestObject = (new SendNauRequest($transaction));
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
        return '/transactions';
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

    /**
     * @param \Exception $exception
     * @return Failed
     */
    protected function getFailedResponseObject(\Exception $exception): Failed
    {
        return new \OmniSynapse\CoreService\Failed\SendNau($exception, $this->transaction);
    }
}
