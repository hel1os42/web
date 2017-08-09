<?php

namespace OmniSynapse\CoreService\Request;

use App\Models\NauModels\Redemption;

/**
 * Class OfferForRedemption
 * @package OmniSynapse\CoreService\Request
 */
class OfferForRedemption implements \JsonSerializable
{
    /** @var string */
    public $offerId;

    /** @var string */
    public $userId;

    /**
     * OfferForRedemption constructor.
     *
     * @param Redemption $redemption
     */
    public function __construct(Redemption $redemption)
    {
        $this->setOfferId($redemption->getId())
            ->setUserId($redemption->getUserId());
    }

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
    public function setOfferId(string $offerId): OfferForRedemption
    {
        $this->offerId = $offerId;
        return $this;
    }

    /**
     * @param string $userId
     * @return OfferForRedemption
     */
    public function setUserId(string $userId): OfferForRedemption
    {
        $this->userId = $userId;
        return $this;
    }
}
