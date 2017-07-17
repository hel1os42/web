<?php

namespace OmniSynapse\CoreService\Request;

/**
 * Class OfferRedemptionRequest
 * @package OmniSynapse\CoreService\Request
 *
 * @property string id
 * @property string user_id
 */
class OfferRedemptionRequest implements \JsonSerializable
{
    /** @var string */
    public $id;

    /** @var string */
    public $user_id;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'user_id' => $this->user_id,
        ];
    }

    /**
     * @param string $id
     * @return OfferRedemptionRequest
     */
    public function setId(string $id) : OfferRedemptionRequest
    {
        $this->id = $id;
        return $this;
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