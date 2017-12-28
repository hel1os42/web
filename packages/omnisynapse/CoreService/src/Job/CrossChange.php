<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Account;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\FailedJob;
use OmniSynapse\CoreService\FailedJob\CrossChange as FailedCrossChange;
use OmniSynapse\CoreService\Request\CrossChange as CrossChangeRequest;
use OmniSynapse\CoreService\Response\CrossChange as CrossChangeResponse;

/**
 * Class CrossChange
 * NS: OmniSynapse\CoreService\Job
 */
class CrossChange extends AbstractJob
{
    /**
     * @var Account
     */
    private $account;
    /**
     * @var float
     */
    private $amount;
    /**
     * @var bool
     */
    private $isIncoming;
    /** @var string */
    private $ethAddress;

    public function __construct(Account $account, string $ethAddress, float $amount, bool $isIncoming, CoreService $coreService)
    {
        parent::__construct($coreService);

        $this->ethAddress = $ethAddress;
        $this->account    = $account;
        $this->amount     = $amount;
        $this->isIncoming = $isIncoming;
    }

    /** @return string */
    public function getHttpMethod(): string
    {
        return 'POST';
    }

    /** @return string */
    public function getHttpPath(): string
    {
        return '/transactions/crosschange';
    }

    /** @return null|\JsonSerializable */
    public function getRequestObject(): ?\JsonSerializable
    {
        return new CrossChangeRequest($this->account->address, $this->ethAddress, $this->amount, $this->isIncoming ? 'in' : 'out');
    }

    /** @return object */
    public function getResponseObject()
    {
        return new CrossChangeResponse();
    }

    /**
     * @param \Exception $exception
     *
     * @return FailedJob
     */
    protected function getFailedResponseObject(\Exception $exception): FailedJob
    {
        return new FailedCrossChange($exception, $this->account, $this->amount, $this->isIncoming);
    }

    public function __sleep()
    {
        $parentProperties = parent::__sleep();
        return array_merge($parentProperties, ['account', 'amount', 'isIncoming']);
    }
}
