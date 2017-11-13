<?php

namespace OmniSynapse\CoreService\Request\Offer;

use Faker\Factory as Faker;
use Tests\TestCase;

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
        $expType = Geo::TYPE_CITY;

        $this->assertEquals($point, $geo->getPoint(), 'point');
        $this->assertEquals($radius, $geo->getRadius(), 'radius');
        $this->assertEquals($city, $geo->getCity(), 'city');
        $this->assertEquals($country, $geo->getCountry(), 'country');
        $this->assertEquals($expType, $geo->getType(), 'type');

        $expected = [
            'type'    => $expType,
            'point'   => null !== $point
                ? $point->jsonSerialize()
                : null,
            'radius'  => $radius,
            'city'    => $city,
            'country' => $country,
        ];

        $this->assertEquals($expected, $geo->jsonSerialize(), 'Expected array is not equals with GEO array');
    }

    /**
     * If we have no point then type = world
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testTypeWorld()
    {
        $faker = Faker::create();

        $point   = null;
        $radius  = null;
        $country = $faker->country;
        $city    = $faker->city;
        $expType = Geo::TYPE_WORLD;

        $geo = new Geo($point, $radius, $city, $country);

        $this->assertEquals($expType, $geo->getType(), 'type');
    }

    /**
     * If we have only point then type = point
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testTypePoint()
    {
        $faker = Faker::create();

        $point   = new Point($faker->latitude, $faker->longitude);
        $radius  = $faker->randomDigitNotNull;
        $country = null;
        $city    = null;
        $expType = Geo::TYPE_POINT;

        $geo = new Geo($point, $radius, $city, $country);

        $this->assertEquals($point, $geo->getPoint(), 'point');
        $this->assertEquals($radius, $geo->getRadius(), 'radius');
        $this->assertEquals($expType, $geo->getType(), 'type');
    }

    /**
     * If we have point and country then type = country
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testCountryPoint()
    {
        $faker = Faker::create();

        $point   = new Point($faker->latitude, $faker->longitude);
        $radius  = $faker->randomDigitNotNull;
        $country = $faker->country;
        $city    = null;
        $expType = Geo::TYPE_COUNTRY;

        $geo = new Geo($point, $radius, $city, $country);

        $this->assertEquals($point, $geo->getPoint(), 'point');
        $this->assertEquals($radius, $geo->getRadius(), 'radius');
        $this->assertEquals($country, $geo->getCountry(), 'country');
        $this->assertEquals($expType, $geo->getType(), 'type');
    }

    /**
     *  If we have point, country and city then type = city
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testCityPoint()
    {
        $faker = Faker::create();

        $point   = new Point($faker->latitude, $faker->longitude);
        $radius  = $faker->randomDigitNotNull;
        $country = $faker->country;
        $city    = $faker->city;
        $expType = Geo::TYPE_CITY;

        $geo = new Geo($point, $radius, $city, $country);

        $this->assertEquals($point, $geo->getPoint(), 'point');
        $this->assertEquals($radius, $geo->getRadius(), 'radius');
        $this->assertEquals($country, $geo->getCountry(), 'country');
        $this->assertEquals($city, $geo->getCity(), 'city');
        $this->assertEquals($expType, $geo->getType(), 'type');
    }
}
