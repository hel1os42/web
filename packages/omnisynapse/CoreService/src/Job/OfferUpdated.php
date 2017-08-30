<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Offer;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\CoreServiceImpl;
use OmniSynapse\CoreService\Request\OfferForUpdate;
use OmniSynapse\CoreService\Response\Offer as OfferResponse;
use OmniSynapse\CoreService\Failed\Failed;

/**
 * Class OfferUpdated
 * @package OmniSynapse\CoreService\Job
 */
class OfferUpdated extends AbstractJob
{
    /** @var OfferForUpdate */
    private $requestObject;

    /** @var Offer */
    private $offer;

    /**
     * OfferUpdated constructor.
     *
     * @param Offer $offer
     * @param CoreServiceImpl $coreService
     */
    public function __construct(Offer $offer, CoreServiceImpl $coreService)
    {
        parent::__construct($coreService);

        $this->offer = $offer;

        /** @var OfferForUpdate requestObject */
        $this->requestObject = new OfferForUpdate($offer);
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
        return 'PUT';
    }

    /**
     * @return string
     */
    public function getHttpPath(): string
    {
        return '/offers/'.$this->requestObject->offerId;
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
        return new \OmniSynapse\CoreService\Failed\OfferUpdated($exception, $this->offer);
    }
}
