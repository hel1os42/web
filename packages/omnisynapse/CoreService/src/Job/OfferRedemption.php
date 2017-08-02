<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\Redemption;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\Request\OfferForRedemption as OfferForRedemptionRequest;
use OmniSynapse\CoreService\Response\OfferForRedemption as OfferForRedemptionResponse;

/**
 * Class OfferRedemption
 * @package OmniSynapse\CoreService\Job
 */
class OfferRedemption extends AbstractJob
{
    /** @var OfferForRedemptionRequest */
    private $requestObject;

    /**
     * OfferRedemption constructor.
     *
     * @param Redemption $redemption
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Redemption $redemption, \GuzzleHttp\Client $client)
    {
        parent::__construct($client);

        /** @var OfferForRedemptionRequest requestObject */
        $this->requestObject = new OfferForRedemptionRequest($redemption);
    }

    /**
     * @return string
     */
    protected function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * @return string
     */
    protected function getHttpPath(): string
    {
        return '/offers/'.$this->requestObject->offerId.'/redemption';
    }

    /**
     * @return \JsonSerializable
     */
    protected function getRequestObject(): \JsonSerializable
    {
        return $this->requestObject;
    }

    /**
     * @return string
     */
    protected function getResponseClass(): string
    {
        return OfferForRedemptionResponse::class;
    }
}
