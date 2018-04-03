<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Offer;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\FailedJob;
use OmniSynapse\CoreService\Request\Offer as OfferRequest;
use OmniSynapse\CoreService\Response\BaseResponse;
use OmniSynapse\CoreService\Response\Offer as OfferResponse;

/**
 * Class OfferCreated
 * @package OmniSynapse\CoreService\Job
 */
class OfferCreated extends AbstractJob
{
    /** @var null|OfferRequest\ */
    private $requestObject;

    /** @var Offer */
    private $offer;

    /**
     * OfferCreated constructor.
     *
     * @param Offer $offer
     * @param CoreService $coreService
     */
    public function __construct(Offer $offer, CoreService $coreService)
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
     * @return null|\JsonSerializable
     */
    public function getRequestObject(): ?\JsonSerializable
    {
        return $this->requestObject;
    }

    /** @return BaseResponse */
    public function getResponseObject(): BaseResponse
    {
        return new OfferResponse;
    }

    /**
     * @param \Exception $exception
     * @return FailedJob
     */
    protected function getFailedResponseObject(\Exception $exception): FailedJob
    {
        return new FailedJob\OfferCreated($exception, $this->offer);
    }

    /**
     * @param \OmniSynapse\CoreService\Response\Offer $responseObject
     */
    protected function fireModelEvents($responseObject): void
    {
        $offer = $this->getModel($responseObject->getId());
        event('eloquent.created: ' . get_class($offer), $offer);
    }

    protected function getConcreteModel($modelId)
    {
        return Offer::query()->withoutGlobalScopes()->findOrFail($modelId);
    }
}
