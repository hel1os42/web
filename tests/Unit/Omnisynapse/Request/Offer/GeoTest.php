<?php

namespace OmniSynapse\CoreService\Request\Offer;

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

        $point   = new Point($faker->latitude, $faker->longitude);
        $geo     = new Geo($point, $radius, $city, $country);
        $expType = Geo::TYPE_POINT;

        $this->assertEquals($point, $geo->getPoint(), 'point');
        $this->assertEquals($radius, $geo->getRadius(), 'radius');
        $this->assertEquals($city, $geo->getCity(), 'city');
        $this->assertEquals($country, $geo->getCountry(), 'country');
        $this->assertEquals($expType, $geo->getType(), 'type');

        $expected = [
            'type'      => $expType,
            'point'     => null !== $point
                ? $point->jsonSerialize()
                : null,
            'radius'    => $radius,
            'city'      => $city,
            'country'   => $country,
        ];

        $this->assertEquals($expected, $geo->jsonSerialize(), 'Expected array is not equals with GEO array');
    }
}
