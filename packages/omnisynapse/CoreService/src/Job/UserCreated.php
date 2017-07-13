<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;

/**
 * Class UserCreated
 * @package OmniSynapse\CoreService\Job
 *
 * @property string id
 * @property string username
 * @property string referrer_id
 */
class UserCreated extends Job
{
    /** @var string */
    private $id = null;

    /** @var string */
    private $username = null;

    /** @var string */
    private $referrer_id = null;

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return Client::METHOD_PUT;
    }

    /**
     * @return string
     */
    public function getHttpPath()
    {
        return '/user';
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getReferrerId()
    {
        return $this->referrer_id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param string $referrer_id
     * @return $this
     */
    public function setReferrerId($referrer_id)
    {
        $this->referrer_id = $referrer_id;
        return $this;
    }

    /**
     * @return array
     */
    private function getArrayParams()
    {
        return [
            'id'            => $this->getId(),
            'username'      => $this->getUsername(),
            'referrer_id'   => $this->getReferrerId(),
        ];
    }
}