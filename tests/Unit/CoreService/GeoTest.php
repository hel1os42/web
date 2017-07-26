<?php

namespace Tests\Unit\CoreService;

use OmniSynapse\CoreService\Request\Offer\Geo;
use OmniSynapse\CoreService\Request\Offer\Point;
use Tests\TestCase;
use Faker\Factory as Faker;

class GeoTest extends TestCase
{
    /**
     * @return void
     */
    public function testGettersAndSetters()
    {
        $faker = Faker::create();

        $radius  = $faker->randomDigitNotNull;
        $city    = $faker->city;
        $country = $faker->country;

        $point   = $faker->boolean()
            ? new Point($faker->latitude, $faker->longitude)
            : null;
        $geo     = new Geo($point, $radius, $city, $country);
        $expType = null !== $point
            ? Geo::TYPE_POINT
            : Geo::TYPE_CITY;

        $this->assertTrue($point === $geo->getPoint(), 'point');
        $this->assertTrue($radius === $geo->getRadius(), 'radius');
        $this->assertTrue($city === $geo->getCity(), 'city');
        $this->assertTrue($country === $geo->getCountry(), 'country');
        $this->assertTrue($expType === $geo->getType(), 'type');

        $jsonSerialize = [
            'type'      => $expType,
            'point'     => null !== $point
                ? $point->jsonSerialize()
                : null,
            'radius'    => $radius,
            'city'      => $city,
            'country'   => $country,
        ];

        $this->assertJsonStringEqualsJsonString(\GuzzleHttp\json_encode($jsonSerialize), \GuzzleHttp\json_encode($geo->jsonSerialize()), 'jsonSerialize');
    }
}
