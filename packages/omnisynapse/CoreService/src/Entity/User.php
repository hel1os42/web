<?php

namespace OmniSynapse\CoreService\Entity;

use Carbon\Carbon;

/**
 * Class User
 * @package OmniSynapse\CoreService\Entity
 *
 * @property string id
 * @property string username
 * @property string referrer_id
 * @property array wallets
 * @property integer level
 * @property integer points
 * @property Carbon created_at
 */
class User
{
    /** @var string */
    public $id;

    /** @var string */
    public $username;

    /** @var string */
    public $referrer_id;

    /** @var array */
    public $wallets;

    /** @var integer */
    public $level;

    /** @var integer */
    public $points;

    /** @var Carbon */
    public $created_at;
}