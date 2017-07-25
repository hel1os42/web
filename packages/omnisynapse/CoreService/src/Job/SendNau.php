<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Exception\RequestException;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Response\SendNau as SendNauResponse;
use OmniSynapse\CoreService\Request\SendNau as SendNauRequest;

class SendNau extends Job
{
    /**
     * SendNau constructor.
     */
    public function __construct()
    {
        throw new RequestException('SendNau job is not finished yet.');

        /** @var SendNau requestObject */
        $this->requestObject = (new SendNauRequest());
    }

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
        return $this->requestObject;
    }

    /**
     * @return string
     */
    protected function getResponseClass() : string
    {
        return SendNauResponse::class;
    }
}
