<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 05.10.17
 * Time: 14:25
 */

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Offer;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\FailedJob;
use OmniSynapse\CoreService\Response\BaseResponse;
use OmniSynapse\CoreService\Response\OfferDeleted as OfferDeletedResponse;

/**
 * Class OfferDeleted
 * @package OmniSynapse\CoreService\Job
 */
class OfferDeleted extends AbstractJob
{
    /** @var string */
    private $offerId;

    /**
     * OfferUpdated constructor.
     *
     * @param Offer $offer
     * @param CoreService $coreService
     */
    public function __construct(Offer $offer, CoreService $coreService)
    {
        parent::__construct($coreService);

        $this->offerId = $offer->id;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        $parentProperties = parent::__sleep();
        return array_merge($parentProperties, ['offerId']);
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return 'DELETE';
    }

    /**
     * @return string
     */
    public function getHttpPath(): string
    {
        return '/offers/'.$this->offerId;
    }

    /**
     * @return null|\JsonSerializable
     */
    public function getRequestObject(): ?\JsonSerializable
    {
        return null;
    }

    /**
     * @return BaseResponse
     */
    public function getResponseObject(): BaseResponse
    {
        return new OfferDeletedResponse($this->offerId);
    }

    /**
     * @param \Exception $exception
     * @return FailedJob
     */
    protected function getFailedResponseObject(\Exception $exception): FailedJob
    {
        return new FailedJob\OfferDeleted($exception, $this->offerId);
    }

    /**
     * @param \OmniSynapse\CoreService\Response\OfferDeleted $responseObject
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function fireModelEvents($responseObject): void
    {
        $offer = $this->getModel($responseObject->getOfferId());
        event('eloquent.deleted: ' . get_class($offer), $offer);
    }

    /**
     * @param $modelId
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function getConcreteModel($modelId)
    {
        return Offer::query()->withoutGlobalScopes()->findOrFail($modelId);
    }
}
