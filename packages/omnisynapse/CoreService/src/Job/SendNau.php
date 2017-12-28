<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Transact;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\FailedJob;
use OmniSynapse\CoreService\Request\SendNau as SendNauRequest;
use OmniSynapse\CoreService\Response\Transaction;

/**
 * Class SendNau
 * @package OmniSynapse\CoreService\Job
 */
class SendNau extends AbstractJob
{
    /** @var null|SendNauRequest */
    private $requestObject;

    /** @var Transact */
    private $transaction;

    /**
     * @var string
     */
    private $httpPath = '/transactions';

    /**
     * SendNau constructor.
     *
     * @param Transact    $transaction
     * @param CoreService $coreService
     */
    public function __construct(Transact $transaction, CoreService $coreService)
    {
        parent::__construct($coreService);

        $this->transaction = $transaction;

        if ($this->transaction->isNoFee()) {
            $this->httpPath = '/transactions/noFee';
        }

        /** @var SendNau requestObject */
        $this->requestObject = (new SendNauRequest($transaction));
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        $parentProperties = parent::__sleep();

        return array_merge($parentProperties, ['requestObject', 'transaction']);
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
        return $this->httpPath;
    }

    /**
     * @return null|\JsonSerializable
     */
    public function getRequestObject(): ?\JsonSerializable
    {
        return $this->requestObject;
    }

    /**
     * @return object
     */
    public function getResponseObject()
    {
        return new Transaction;
    }

    /**
     * @param \Exception $exception
     *
     * @return FailedJob
     */
    protected function getFailedResponseObject(\Exception $exception): FailedJob
    {
        return new FailedJob\SendNau($exception, $this->transaction);
    }
}
