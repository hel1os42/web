<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;

/**
 * Class OfferRedemption
 * @package OmniSynapse\CoreService\Job
 *
 * @property string id
 * @property string user_id
 */
class OfferRedemption extends Job
{
    /** @var string */
    private $method = Client::METHOD_POST;

    /** @var string */
    private $id = null;

    /** @var string */
    private $user_id = null;

    /**
     * Execute the job.
     *
     * @return object
     */
    public function handle()
    {
        return $this->client->request($this->method, $this->getPath(), $this->getArrayParams())->getContent();
    }

    /**
     * @return string
     */
    private function getPath()
    {
        return '/offers/'.$this->getId().'/redemption';
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
    public function getUserId()
    {
        return $this->user_id;
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
     * @param string $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return array
     */
    private function getArrayParams()
    {
        return [
            'user_id' => $this->getUserId(),
        ];
    }
}