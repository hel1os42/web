<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Redemption;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\CoreServiceImpl;
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
     * @param CoreServiceImpl $coreService
     */
    public function __construct(Redemption $redemption, CoreServiceImpl $coreService)
    {
        parent::__construct($coreService);

        $this->redemption = $redemption;

        /** @var OfferForRedemptionRequest requestObject */
        $this->requestObject = new OfferForRedemptionRequest($redemption);
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        $parentProperties = parent::__sleep();
        return array_merge($parentProperties, ['requestObject', 'redemption']);
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
