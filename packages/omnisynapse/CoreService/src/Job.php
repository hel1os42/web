<?php

namespace OmniSynapse\CoreService;

abstract class Job
{
    /**
     * @var string
     */
    private $path = '/user';

    /**
     * @var string
     */
    private $method = 'PUT';

    /**
     * User UUID
     *
     * @var string
     */
    private $id;

    /**
     * User name
     *
     * @var string
     */
    private $username;

    /**
     * User referrer id
     *
     * @var string
     */
    private $referrerId;

    /**
     * Job constructor.
     *
     * @param string $id
     * @param string $username
     * @param string $referrerId
     */
    public function __construct($id, $username, $referrerId)
    {
        $this->id = $id;
        $this->username = $username;
        $this->referrerId = $referrerId;
    }

    /**
     * @return void
     */
    public function handle()
    {
        // ...
    }
}