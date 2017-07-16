<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\SendNauRequest;
use OmniSynapse\CoreService\Response\SendNauResponse;

class SendNau extends Job
{
    /**
     * @return string
     */
    public function getHttpMethod() : string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getHttpPath() : string
    {
        return '';
    }

    /**
     * @return \JsonSerializable
     */
    protected function getRequestObject() : \JsonSerializable
    {
        return new SendNauRequest();
    }

    /**
     * @return SendNauResponse
     */
    protected function getResponseClass()
    {
        return new SendNauResponse();
    }
}