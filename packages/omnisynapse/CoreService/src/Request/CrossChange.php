<?php

namespace OmniSynapse\CoreService\Request;

/**
 * Class CrossChange
 * NS: OmniSynapse\CoreService\Request
 */
class CrossChange implements \JsonSerializable
{
    public $nauAddress;
    public $ethAddress;
    public $amount;
    public $direction;

    public function __construct(string $nauAddress, string $ethAddress, string $amount, string $direction)
    {
        $this->nauAddress = $nauAddress;
        $this->ethAddress = $ethAddress;
        $this->direction  = $direction;
        $this->amount     = $amount;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            'nauAddress' => $this->nauAddress,
            'ethAddress' => $this->ethAddress,
            'amount'     => $this->amount,
            'direction'  => $this->direction,
        ];
    }
}
