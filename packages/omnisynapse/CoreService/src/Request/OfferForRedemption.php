<?php

namespace OmniSynapse\CoreService\Request;

/**
 * Class OfferForRedemption
 * @package OmniSynapse\CoreService\Request
 *
 * @property string id
 * @property string user_id
 */
class OfferForRedemption implements \JsonSerializable
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
     * @return OfferForRedemption
     */
    public function setId(string $id) : OfferForRedemption
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $user_id
     * @return OfferForRedemption
     */
    public function setUserId(string $user_id) : OfferForRedemption
    {
        $this->user_id = $user_id;
        return $this;
    }
}