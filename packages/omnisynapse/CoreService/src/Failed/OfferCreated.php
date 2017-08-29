<?php

namespace OmniSynapse\CoreService\Failed;

use App\Models\NauModels\Offer;

/**
 * Class OfferCreated
 * @package OmniSynapse\CoreService\Failed
 */
class OfferCreated extends Failed
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
