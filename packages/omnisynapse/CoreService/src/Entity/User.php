<?php

namespace OmniSynapse\CoreService\Entity;

/**
 * Class User
 * @package OmniSynapse\CoreService\Entity
 *
 * @property string id
 * @property string username
 * @property string referrer_id
 */
class User
{
    /** @var string */
    protected $id = null;

    /** @var string */
    protected $username = null;

    /** @var string */
    protected $referrer_id = null;
}