<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\Nau;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\SendNauRequest;
use OmniSynapse\CoreService\Response\SendNauResponse;

class SendNau extends Job
{
    /**
     * SendNau constructor.
     * @param Nau $nau
     */
    public function __construct(Nau $nau)
    {
        parent::__construct();

        /** @var SendNauRequest requestObject */
        $this->requestObject = $this->getRequestObject();
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