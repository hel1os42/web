<?php

namespace OmniSynapse\CoreService\Response;

use OmniSynapse\CoreService\Entity\Offer;

class OfferRedemptionResponse extends Offer
{
    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUserId() : string
    {
        return $this->user_id;
    }
}