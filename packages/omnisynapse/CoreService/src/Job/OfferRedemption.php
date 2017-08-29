<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Redemption;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\Request\OfferForRedemption as OfferForRedemptionRequest;
use OmniSynapse\CoreService\Response\OfferForRedemption as OfferForRedemptionResponse;
use OmniSynapse\CoreService\Failed\Failed;

/**
 * Class OfferRedemption
 * @package OmniSynapse\CoreService\Job
 */
class OfferRedemption extends AbstractJob
{
    /** @var OfferForRedemptionRequest */
    private $requestObject;

    /** @var Redemption */
    private $redemption;

    /**
     * OfferRedemption constructor.
     *
     * @param Redemption $redemption
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Redemption $redemption, \GuzzleHttp\Client $client)
    {
        parent::__construct($client);

        $this->redemption = $redemption;

        /** @var OfferForRedemptionRequest requestObject */
        $this->requestObject = new OfferForRedemptionRequest($redemption);
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * @return string
     */
    public function getHttpPath(): string
    {
        return '/offers/'.$this->requestObject->offerId.'/redemption';
    }

    /**
     * @return \JsonSerializable
     */
    public function getRequestObject(): \JsonSerializable
    {
        return $this->requestObject;
    }

    /**
     * @return string
     */
    public function getResponseClass(): string
    {
        return OfferForRedemptionResponse::class;
    }

    /**
     * @param \Exception $exception
     * @return Failed
     */
    protected function getFailedResponseObject(\Exception $exception): Failed
    {
        return new \OmniSynapse\CoreService\Failed\OfferRedemption($exception, $this->redemption);
    }
}
