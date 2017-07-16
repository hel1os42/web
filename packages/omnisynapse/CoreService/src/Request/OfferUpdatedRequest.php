<?php

namespace OmniSynapse\CoreService\Request;

use OmniSynapse\CoreService\Entity\Offer;

class OfferUpdatedRequest extends Offer implements \JsonSerializable
{
    public function jsonSerialize()
    {

    }

    /**
     * @param string $id
     * @return OfferUpdatedRequest
     */
    public function setId($id) : OfferUpdatedRequest
    {
        $this->id = $id;
        return $this;
    }
}