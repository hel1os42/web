<?php

namespace OmniSynapse\CoreService\FailedJob;

use App\Models\NauModels\Account;
use OmniSynapse\CoreService\FailedJob;

/**
 * Class CrossChange
 * NS: OmniSynapse\CoreService\FailedJob
 */
class CrossChange extends FailedJob
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

    public function __construct(\Exception $exception, Account $account, float $amount, bool $isIncoming)
    {
        parent::__construct($exception);

        $this->account    = $account;
        $this->amount     = $amount;
        $this->isIncoming = $isIncoming;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return bool
     */
    public function isIncoming(): bool
    {
        return $this->isIncoming;
    }
}
