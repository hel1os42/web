<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Redemption;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\FailedJob;
use OmniSynapse\CoreService\Request\OfferForRedemption as OfferForRedemptionRequest;
use OmniSynapse\CoreService\Response\BaseResponse;
use OmniSynapse\CoreService\Response\OfferForRedemption as OfferForRedemptionResponse;

/**
 * Class OfferRedemption
 * @package OmniSynapse\CoreService\Job
 */
class OfferRedemption extends AbstractJob
{
    /** @var null|OfferForRedemptionRequest */
    private $requestObject;

    /** @var Redemption */
    private $redemption;

    /**
     * OfferRedemption constructor.
     *
     * @param Redemption $redemption
     * @param CoreService $coreService
     */
    public function __construct(Redemption $redemption, CoreService $coreService)
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
     * @return null|\JsonSerializable
     */
    public function getRequestObject(): ?\JsonSerializable
    {
        return $this->requestObject;
    }

    /** @return BaseResponse */
    public function getResponseObject(): BaseResponse
    {
        return new OfferForRedemptionResponse;
    }

    /**
     * @param \Exception $exception
     * @return FailedJob
     */
    protected function getFailedResponseObject(\Exception $exception): FailedJob
    {
        return new FailedJob\OfferRedemption($exception, $this->redemption);
    }

    /**
     * @param OfferForRedemptionResponse $responseObject
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function fireModelEvents($responseObject): void
    {
        $redemption = Redemption::query()->withoutGlobalScopes()->findOrFail($responseObject->getId());
        event('eloquent.created: ' . get_class($redemption), $redemption);
    }
}
