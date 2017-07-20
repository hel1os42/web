<?php

namespace OmniSynapse\CoreService\Job;

use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Exception\RequestException;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\OfferForUpdate;
use OmniSynapse\CoreService\Response\Offer as OfferResponse;

// TODO: project models

/**
 * Class OfferUpdated
 * @package OmniSynapse\CoreService\Job
 */
class OfferUpdated extends Job
{
    /**
     * OfferUpdated constructor.
     * @param XXX $offer
     */
    public function __construct(XXX $offer)
    {
        /** @var OfferForUpdate requestObject */
        $this->requestObject = (new OfferForUpdate())
            ->setId($offer->getId())
            ->setOwnerId($offer->getOwnerId())
            ->setName($offer->getName())
            ->setDescription($offer->getDescription())
            ->setCategoryId($offer->getCategoryId())
            ->setGeo($offer->geo)
            ->setLimits($offer->limits)
            ->setReward($offer->getReward())
            ->setStartDate($offer->getStartDate())
            ->setEndDate($offer->getEndDate())
            ->setStartTime($offer->getStartTime())
            ->setEndTime($offer->getEndTime());
    }

    /**
     * @return string
     */
    public function getHttpMethod() : string
    {
        return Client::METHOD_PUT;
    }

    /**
     * @return string
     */
    public function getHttpPath() : string
    {
        return '/offers/'.$this->requestObject->id;
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
        return OfferResponse::class;
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

        $this->changeLoggerPath('OfferUpdated');
        logger()->error($logMessage);

        throw new RequestException($logMessage);
    }
}