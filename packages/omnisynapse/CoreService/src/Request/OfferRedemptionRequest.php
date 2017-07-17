<?php

namespace OmniSynapse\CoreService\Request;

use OmniSynapse\CoreService\Entity\Offer;

class OfferRedemptionRequest extends Offer implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [
            'user_id' => $this->user_id,
        ];
    }

    /**
     * @param string $user_id
     * @return OfferRedemptionRequest
     */
    public function setUserId(string $user_id) : OfferRedemptionRequest
    {
        $this->user_id = $user_id;
        return $this;
    }
}