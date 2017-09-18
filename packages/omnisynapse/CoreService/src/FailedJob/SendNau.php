<?php

namespace OmniSynapse\CoreService\FailedJob;

use App\Models\NauModels\Transact;
use OmniSynapse\CoreService\FailedJob;

/**
 * Class SendNau
 * @package OmniSynapse\CoreService\FailedJob
 */
class SendNau extends FailedJob
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
