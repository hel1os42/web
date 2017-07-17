<?php

namespace OmniSynapse\CoreService\Entity;

/**
 * Class Wallet
 * @package OmniSynapse\CoreService\Entity
 *
 * @property string $currency
 * @property string $address
 * @property float $balance
 */
class Wallet
{
    /** @var string */
    public $currency;

    /** @var string */
    public $address;

    /** @var float */
    public $balance;
}