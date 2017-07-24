<?php

namespace OmniSynapse\CoreService\Request;

/**
 * Class OfferForUpdate.
 * @package OmniSynapse\CoreService\Request
 */
class OfferForUpdate extends Offer implements \JsonSerializable
{
    /** @var string */
    public $offerId;

    /**
     * @param string $offerId
     * @return OfferForUpdate
     */
    public function setOfferId(string $offerId) : OfferForUpdate
    {
        $this->offerId = $offerId;
        return $this;
    }
}
