<?php

namespace OmniSynapse\CoreService\Response;

use Carbon\Carbon;

/**
 * Class SendNau
 * @package OmniSynapse\CoreService\Response
 *
 * @property string transaction_id
 * @property int source_account_id
 * @property int destination_account_id
 * @property float amount
 * @property string status
 * @property string created_at
 * @property string type
 * @property array feeTransactions
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Transaction
{
    /** @var string */
    public $transaction_id;

    /** @var int */
    public $source_account_id;

    /** @var int */
    public $destination_account_id;

    /** @var float */
    public $amount;

    /** @var string */
    public $status;

    /** @var string */
    public $created_at;

    /** @var string */
    public $type;

    /** @var array */
    public $feeTransactions;

    /**
     * @return string
     */
    public function getTransactionId() : string
    {
        return $this->transaction_id;
    }

    /**
     * @return int
     */
    public function getSourceAccountId() : int
    {
        return $this->source_account_id;
    }

    /**
     * @return int
     */
    public function getDestinationAccountId() : int
    {
        return $this->destination_account_id;
    }

    /**
     * @return float
     */
    public function getAmount() : float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getStatus() : string
    {
        return $this->status;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt() : Carbon
    {
        return Carbon::parse($this->created_at);
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getFeeTransactions() : array
    {
        return $this->feeTransactions;
    }
}
