<?php

namespace OmniSynapse\CoreService\Failed;

use App\Models\NauModels\Transact;

/**
 * Class TransactionNotification
 * @package OmniSynapse\CoreService\Failed;
 */
class TransactionNotification extends Failed
{
    /** @var Transact */
    private $transaction;

    /**
     * @param \Exception $exception
     * @param Transact|null $transaction
     */
    public function __construct(\Exception $exception, Transact $transaction = null)
    {
        parent::__construct($exception);
        $this->transaction = $transaction;
    }

    /**
     * @return Transact|null
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}
