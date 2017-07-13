<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;

class SendNau extends Job
{
    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getHttpPath()
    {
        return '';
    }
}