<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Offer;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\CoreServiceImpl;
use OmniSynapse\CoreService\Request\Offer as OfferRequest;
use OmniSynapse\CoreService\Response\Offer as OfferResponse;
use OmniSynapse\CoreService\Failed\Failed;

/**
 * Class OfferCreated
 * @package OmniSynapse\CoreService\Job
 */
class OfferCreated extends AbstractJob
{
    /** @var OfferRequest\ */
    private $requestObject;

    /** @var Offer */
    private $offer;

    /**
     * OfferCreated constructor.
     *
     * @param Offer $offer
     * @param CoreServiceImpl $coreService
     */
    public function __construct(Offer $offer, CoreServiceImpl $coreService)
    {
        parent::__construct($coreService);

        $this->offer = $offer;

        /** @var OfferRequest requestObject */
        $this->requestObject = new OfferRequest($offer);
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        $parentProperties = parent::__sleep();
        return array_merge($parentProperties, ['requestObject', 'offer']);
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
        return '/offers';
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
        return OfferResponse::class;
    }

    /**
     * @param \Exception $exception
     * @return Failed
     */
    protected function getFailedResponseObject(\Exception $exception): Failed
    {
        return new \OmniSynapse\CoreService\Failed\OfferCreated($exception, $this->offer);
    }
}
