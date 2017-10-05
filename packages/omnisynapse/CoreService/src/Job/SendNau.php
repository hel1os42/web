<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Transact;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Response\Transaction;
use OmniSynapse\CoreService\Request\SendNau as SendNauRequest;
use OmniSynapse\CoreService\FailedJob;

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
     * SendNau constructor.
     *
     * @param Transact $transaction
     * @param CoreService $coreService
     */
    public function __construct(Transact $transaction, CoreService $coreService)
    {
        parent::__construct($coreService);

        $this->transaction = $transaction;

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
        return '/transactions';
    }

    /**
     * @return null|\JsonSerializable
     */
    public function getRequestObject(): ?\JsonSerializable
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
     * @return FailedJob
     */
    protected function getFailedResponseObject(\Exception $exception): FailedJob
    {
        return new FailedJob\SendNau($exception, $this->transaction);
    }
}
