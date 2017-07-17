<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Entity\Redemption;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\OfferRedemptionRequest;
use OmniSynapse\CoreService\Response\OfferRedemptionResponse;

/**
 * Class OfferRedemption
 * @package OmniSynapse\CoreService\Job
 */
class OfferRedemption extends Job
{
    private $redemption;

    public function __construct(Redemption $redemption)
    {
        parent::__construct();

        /** @var Redemption redemption */
        $this->redemption = $redemption;

        /** @var OfferRedemptionRequest requestObject */
        $this->requestObject = (new OfferRedemptionRequest())
            ->setUserId($redemption->user_id);
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
        return '/offers/'.$this->redemption->id.'/redemption';
    }

    /**
     * @return \JsonSerializable
     */
    protected function getRequestObject() : \JsonSerializable
    {
        return new OfferRedemptionRequest();
    }

    /**
     * @return string
     */
    protected function getResponseClass() : string
    {
        return OfferRedemptionResponse::class;
    }
}