<?php

namespace OmniSynapse\CoreService\Failed;

use App\Models\NauModels\Transact;
use OmniSynapse\CoreServise\Failed\Failed;

/**
 * Class SendNauFailed
 * @package OmniSynapse\CoreService\Job
 */
class SendNau extends Failed
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
