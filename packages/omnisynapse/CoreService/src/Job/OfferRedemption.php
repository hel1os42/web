<?php

namespace OmniSynapse\CoreService\Job;

use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Exception\RequestException;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\OfferForRedemption as OfferForRedemptionRequest;
use OmniSynapse\CoreService\Response\OfferForRedemption as OfferForRedemptionResponse;

// TODO: project models

/**
 * Class OfferRedemption
 * @package OmniSynapse\CoreService\Job
 */
class OfferRedemption extends Job
{
    /**
     * OfferRedemption constructor.
     * @param XXX $offer
     */
    public function __construct(XXX $offer)
    {
        /** @var OfferForRedemptionRequest requestObject */
        $this->requestObject = (new OfferForRedemptionRequest())
            ->setId($offer->getId())
            ->setUserId($offer->getUserId());
    }

    /**
     * @return string
     */
    public function getHttpMethod() : string
    {
        return Client::METHOD_POST;
    }

    /**
     * @return string
     */
    public function getHttpPath() : string
    {
        return '/offers/'.$this->requestObject->id.'/redemption';
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
        return OfferForRedemptionResponse::class;
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

        $this->changeLoggerPath('OfferRedemption');
        logger()->error($logMessage);

        throw new RequestException($logMessage);
    }
}