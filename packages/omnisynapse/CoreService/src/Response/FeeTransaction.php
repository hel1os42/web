<?php

namespace OmniSynapse\CoreService\Response;

use Carbon\Carbon;

/**
 * Class Limits
 * @package OmniSynapse\CoreService\FeeTransaction
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class FeeTransaction implements \JsonSerializable
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

    /** @var FeeTransaction[] */
    public $feeTransactions;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'transaction_id'         => $this->getTransactionId(),
            'source_account_id'      => $this->getSourceAccountId(),
            'destination_account_id' => $this->getDestinationAccountId(),
            'amount'                 => $this->getAmount(),
            'status'                 => $this->getStatus(),
            'created_at'             => $this->getCreatedAt()->format('Y-m-d H:i:sO'),
            'type'                   => $this->getType(),
            'feeTransactions'        => $this->getFeeTransactions(),
        ];
    }

    /**
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transaction_id;
    }

    /**
     * @return int
     */
    public function getSourceAccountId(): int
    {
        return $this->source_account_id;
    }

    /**
     * @return int
     */
    public function getDestinationAccountId(): int
    {
        return $this->destination_account_id;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return Carbon::parse($this->created_at);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getFeeTransactions(): array
    {
        return $this->feeTransactions;
    }
}
