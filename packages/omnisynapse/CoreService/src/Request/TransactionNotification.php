<?php

namespace OmniSynapse\CoreService\Request;

use App\Models\Transact;

/**
 * Class TransactionNotification
 * @package OmniSynapse\CoreService\Request
 *
 * @property string txid
 * @property string category
 * @property string from
 * @property string to
 * @property float amount
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class TransactionNotification implements \JsonSerializable
{
    /** @var string $txid */
    public $txid;

    /** @var string $category */
    public $category;

    /** @var string $from */
    public $from;

    /** @var string $to */
    public $to;

    /** @var float $amount */
    public $amount;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'txid'     => $this->txid,
            'category' => $this->category,
            'from'     => $this->from,
            'to'       => $this->to,
            'amount'   => $this->amount,
        ];
    }

    /**
     * TransactionNotification constructor.
     *
     * @param Transact $transaction
     * @param string $category
     */
    public function __construct(Transact $transaction, $category)
    {
        $source      = $transaction->source;
        $destination = $transaction->destination;

        $this->setTxid($transaction->getId())
            ->setCategory($category)
            ->setFrom(null !== $source ? $source->getId() : null)
            ->setTo(null !== $destination ? $destination->getId() : null)
            ->setAmount($transaction->getAmount());
    }

    /**
     * @param string $txid
     * @return TransactionNotification
     */
    public function setTxid(string $txid) : TransactionNotification
    {
        $this->txid = $txid;
        return $this;
    }

    /**
     * @param string $category
     * @return TransactionNotification
     */
    public function setCategory(string $category) : TransactionNotification
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @param string $from
     * @return TransactionNotification
     */
    public function setFrom(string $from) : TransactionNotification
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param string $to
     * @return TransactionNotification
     */
    public function setTo(string $to) : TransactionNotification
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @param float $amount
     * @return TransactionNotification
     */
    public function setAmount(float $amount) : TransactionNotification
    {
        $this->amount = $amount;
        return $this;
    }
}
