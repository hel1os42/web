<?php

namespace OmniSynapse\CoreService\Response;

use OmniSynapse\CoreService\Entity\Offer;

class OfferUpdatedResponse extends Offer
{
    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }
}