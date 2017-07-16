<?php

namespace OmniSynapse\CoreService\Request;

use OmniSynapse\CoreService\Entity\Offer;

class OfferRedemptionRequest extends Offer implements \JsonSerializable
{
    public function jsonSerialize()
    {

    }

    /**
     * @param string $id
     * @return OfferRedemptionRequest
     */
    public function setId($id) : OfferRedemptionRequest
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $user_id
     * @return OfferRedemptionRequest
     */
    public function setUserId($user_id) : OfferRedemptionRequest
    {
        $this->user_id = $user_id;
        return $this;
    }
}