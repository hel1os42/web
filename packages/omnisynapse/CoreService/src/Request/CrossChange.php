<?php

namespace OmniSynapse\CoreService\Request;

/**
 * Class CrossChange
 * NS: OmniSynapse\CoreService\Request
 */
class CrossChange implements \JsonSerializable
{
    public $nauAddress;
    public $amount;
    public $direction;

    public function __construct(string $nauAddress, string $amount, string $direction)
    {
        $this->nauAddress = $nauAddress;
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
            'amount'     => $this->amount,
            'direction'  => $this->direction,
        ];
    }
}
