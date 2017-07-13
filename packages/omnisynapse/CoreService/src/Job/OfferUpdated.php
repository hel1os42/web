<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;

/**
 * Class OfferUpdated
 * @package OmniSynapse\CoreService\Job
 *
 * @property string id
 */
class OfferUpdated extends Job
{
    /** @var string */
    private $id = '';

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return Client::METHOD_PUT;
    }

    /**
     * @return string
     */
    public function getHttpPath()
    {
        return '/offer/'.$this->getId();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return array
     */
    private function getArrayParams()
    {
        return [
            'id'            => $this->getId(),
            'owner_id'      => $this->getOwnerId(),
            'name'          => $this->getName(),
            'description'   => $this->getDescription(),
            'category_id'   => $this->getCategoryId(),
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