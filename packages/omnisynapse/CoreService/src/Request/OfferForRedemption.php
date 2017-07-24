<?php

namespace OmniSynapse\CoreService\Request;

/**
 * Class OfferForRedemption
 * @package OmniSynapse\CoreService\Request
 *
 * @property string offerId
 * @property string userId
 */
class OfferForRedemption implements \JsonSerializable
{
    /** @var string */
    public $offerId;

    /** @var string */
    public $userId;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'user_id' => $this->userId,
        ];
    }

    /**
     * @param string $offerId
     * @return OfferForRedemption
     */
    public function setOfferId(string $offerId) : OfferForRedemption
    {
        $this->offerId = $offerId;
        return $this;
    }

    /**
     * @param string $userId
     * @return OfferForRedemption
     */
    public function setUserId(string $userId) : OfferForRedemption
    {
        $this->userId = $userId;
        return $this;
    }
}
