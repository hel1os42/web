<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;

/**
 * Class OfferCreated
 * @package OmniSynapse\CoreService\Job
 */
class OfferCreated extends Job
{
    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return Client::METHOD_POST;
    }

    /**
     * @return string
     */
    public function getHttpPath()
    {
        return '/offer';
    }

    /**
     * @return array
     */
    private function getArrayParams()
    {
        return [
            'owner_id'         => $this->getOwnerId(),
            'name'          => $this->getName(),
            'description'   => $this->getDescription(),
            'category_id'      => $this->getCategoryId(),
            'geo' => [
                'type'      => $this->getGeoType(),
                'point'     => [
                    'lat'   => $this->getGeoPointLat(),
                    'long'  => $this->getGeoPointLong(),
                ],
                'radius'    => $this->getGeoRadius(),
                'city'      => $this->getGeoCity(),
                'country'   => $this->getGeoCountry(),
            ],
            'limits' => [
                'offers'    => $this->getLimitsOffers(),
                'per_day'   => $this->getLimitsPerDay(),
                'per_user'  => $this->getLimitsPerUser(),
                'min_level' => $this->getLimitsMinLevel(),
            ],
            'reward'        => $this->getReward(),
            'start_date'    => $this->getStartDate(),
            'end_date'      => $this->getEndDate(),
            'start_time'    => $this->getStartTime(),
            'end_time'      => $this->getEndTime(),
        ];
    }
}