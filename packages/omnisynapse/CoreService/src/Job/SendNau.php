<?php

namespace OmniSynapse\CoreService\Job;

use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\Exception\RequestException;
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

    /**
     * @param Response $response
     * @throws RequestException
     */
    public function handleError(Response $response)
    {
        $errorMessage = isset($this->responseContent->error)
            ? $this->responseContent->error
            : 'undefined exception reason';
        $requestParams = serialize($this->requestObject->jsonSerialize());
        $logMessage = 'Exception while executing '.self::class.'. Response message: `'.$errorMessage.'`, status: `'.$response->getStatusCode().'.`, Request: '.$requestParams.'.';

        $this->changeLoggerPath('SendNau');
        logger()->error($logMessage);

        throw new RequestException($logMessage);
    }
}