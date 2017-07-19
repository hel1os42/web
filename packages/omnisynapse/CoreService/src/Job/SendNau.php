<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Response\SendNau as SendNauResponse;
use OmniSynapse\CoreService\Request\SendNau as SendNauRequest;

// TODO: project models

class SendNau extends Job
{
    /**
     * SendNau constructor.
     * @param XXX $nau
     */
    public function __construct(XXX $nau)
    {
        parent::__construct();

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