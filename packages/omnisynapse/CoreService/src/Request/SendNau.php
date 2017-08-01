<?php

namespace OmniSynapse\CoreService\Request;

use App\Models\Transact;

/**
 * Class SendNau
 * @package OmniSynapse\CoreService\Request
 */
class SendNau implements \JsonSerializable
{
    /** @var integer $id */
    public $sourceAccountId;

    /** @var int $destinationAccountId */
    public $destinationAccountId;

    /** @var float $amount */
    public $amount;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'source_account_id'      => $this->sourceAccountId,
            'destination_account_id' => $this->destinationAccountId,
            'amount'                 => $this->amount,
        ];
    }

    /**
     * SendNau constructor.
     *
     * @param Transact $transaction
     */
    public function __construct(Transact $transaction)
    {
        $this->setSourceAccountId($transaction->getSourceAccountId())
            ->setDestinationAccountId($transaction->getDestinationAccountId())
            ->setAmount($transaction->getAmount());
    }

    /**
     * @param string $sourceAccountId
     * @return SendNau
     */
    public function setSourceAccountId(string $sourceAccountId) : SendNau
    {
        $this->sourceAccountId = $sourceAccountId;
        return $this;
    }

    /**
     * @param string $destinationAccountId
     * @return SendNau
     */
    public function setDestinationAccountId(string $destinationAccountId) : SendNau
    {
        $this->destinationAccountId = $destinationAccountId;
        return $this;
    }

    /**
     * @param float $amount
     * @return SendNau
     */
    public function setAmount(float $amount) : SendNau
    {
        $this->amount = $amount;
        return $this;
    }
}
