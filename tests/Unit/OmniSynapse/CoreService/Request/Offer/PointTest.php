<?php

namespace OmniSynapse\CoreService\Request\Offer;

use Faker\Factory as Faker;
use Tests\TestCase;

class PointTest extends TestCase
{
    /**
     * @return void
     */
    public function testGettersAndSetters()
    {
        $faker = Faker::create();
        $lat   = $faker->latitude;
        $lng   = $faker->longitude;
        $point = new Point($lat, $lng);

        $this->assertEquals($lat, $point->getLat(), 'latitude');
        $this->assertEquals($lng, $point->getLon(), 'longitude');
    }
}
