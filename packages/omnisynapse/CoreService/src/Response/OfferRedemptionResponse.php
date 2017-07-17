<?php

namespace OmniSynapse\CoreService\Response;

use Carbon\Carbon;

/**
 * Class OfferRedemptionResponse
 * @package OmniSynapse\CoreService\Response
 *
 * @property string id
 * @property string offer_id
 * @property string user_id
 * @property integer points
 * @property string rewarded_id
 * @property float amount
 * @property float fee
 * @property Carbon created_at
 */
class OfferRedemptionResponse
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
    public function getOfferId() : string
    {
        return $this->offer_id;
    }

    /**
     * @return string
     */
    public function getUserId() : string
    {
        return $this->user_id;
    }

    /**
     * @return float
     */
    public function getPoints() : float
    {
        return $this->points;
    }

    /**
     * @return string
     */
    public function getRewardedId() : string
    {
        return $this->rewarded_id;
    }

    /**
     * @return float
     */
    public function getAmount() : float
    {
        return $this->amount;
    }

    /**
     * @return float
     */
    public function getFee() : float
    {
        return $this->fee;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt() : Carbon
    {
        return $this->created_at;
    }
}