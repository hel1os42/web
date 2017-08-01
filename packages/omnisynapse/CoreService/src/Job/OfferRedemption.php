<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\Redemption;
use OmniSynapse\CoreService\CoreServiceClient;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\OfferForRedemption as OfferForRedemptionRequest;
use OmniSynapse\CoreService\Response\OfferForRedemption as OfferForRedemptionResponse;

/**
 * Class OfferRedemption
 * @package OmniSynapse\CoreService\Job
 */
class OfferRedemption extends Job
{
    /**
     * OfferRedemption constructor.
     *
     * @param Redemption $redemption
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Redemption $redemption, \GuzzleHttp\Client $client=null)
    {
        $this->guzzleClient = $client;

        /** @var OfferForRedemptionRequest requestObject */
        $this->requestObject = new OfferForRedemptionRequest($redemption);
    }

    /**
     * @return string
     */
    public function getHttpMethod() : string
    {
        return CoreServiceClient::METHOD_POST;
    }

    /**
     * @return string
     */
    public function getHttpPath() : string
    {
        return '/offers/'.$this->requestObject->offerId.'/redemption';
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
}
