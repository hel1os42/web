<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Traits\OfferTrait;

/**
 * Class OfferCreated
 * @package OmniSynapse\CoreService\Job
 */
class OfferCreated extends Job
{
    use OfferTrait;

    /** @var string  */
    private $path = '/offer';

    /** @var string */
    private $method = Client::METHOD_POST;

    /**
     * Execute the job.
     *
     * @return object
     */
    public function handle()
    {
        return $this->client->request($this->method, $this->path, $this->getArrayParams())->getContent();
    }

    /**
     * @return array
     */
    private function getArrayParams()
    {
        return [
            'owner'         => $this->getOwnerId(), // TODO: in OfferUpdated we have owner_id
            'name'          => $this->getName(),
            'description'   => $this->getDescription(),
            'category'      => $this->getCategoryId(), // TODO: in OfferUpdated we have category_id
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