<?php

namespace OmniSynapse\CoreService\Response;

/**
 * Class Wallet
 * @package OmniSynapse\CoreService\Response
 */
class Wallet implements \JsonSerializable
{
    /** @var string */
    public $currency;

    /** @var string */
    public $address;

    /** @var float */
    public $balance;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'currency' => $this->getCurrency(),
            'address'  => $this->getAddress(),
            'balance'  => $this->getBalance(),
        ];
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }
}
