<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\SendNauRequest;
use OmniSynapse\CoreService\Response\SendNauResponse;

class SendNau extends Job
{
    /**
     * SendNau constructor.
     * @param SendNauRequest $nau
     */
    public function __construct(SendNauRequest $nau)
    {
        parent::__construct();

        /** @var SendNauRequest requestObject */
        $this->requestObject = (new SendNauRequest);
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
        return new SendNauRequest();
    }

    /**
     * @return string
     */
    protected function getResponseClass() : string
    {
        return SendNauResponse::class;
    }
}