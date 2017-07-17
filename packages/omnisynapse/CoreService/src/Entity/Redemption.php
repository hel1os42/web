<?php

namespace OmniSynapse\CoreService\Entity;

use Carbon\Carbon;

/**
 * Class Wallet
 * @package OmniSynapse\CoreService\Entity
 *
 * @property string $id
 * @property string $offer_id
 * @property string $user_id
 * @property integer $points
 * @property string $rewarded_id
 * @property float $amount
 * @property float $fee
 * @property Carbon $created_at
 */
class Redemption
{
    /** @var string */
    public $id;

    /** @var string */
    public $offer_id;

    /** @var string */
    public $user_id;

    /** @var integer */
    public $points;

    /** @var string */
    public $rewarded_id;

    /** @var float */
    public $amount;

    /** @var float */
    public $fee;

    /** @var Carbon */
    public $created_at;
}