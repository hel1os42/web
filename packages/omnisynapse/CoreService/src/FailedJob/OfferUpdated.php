<?php

namespace OmniSynapse\CoreService\FailedJob;

use App\Models\NauModels\Offer;
use OmniSynapse\CoreService\FailedJob;

/**
 * Class OfferUpdated
 * @package OmniSynapse\CoreService\FailedJob
 */
class OfferUpdated extends FailedJob
{
    /** @var Offer */
    private $offer;

    /**
     * @param \Exception $exception
     * @param Offer|null $offer
     */
    public function __construct(\Exception $exception, Offer $offer = null)
    {
        parent::__construct($exception);
        $this->offer = $offer;
    }

    /**
     * @return Offer|null
     */
    public function getOffer()
    {
        return $this->offer;
    }
}
