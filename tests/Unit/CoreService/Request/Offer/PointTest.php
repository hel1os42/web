<?php

namespace Tests\Unit\CoreService\Request\Offer;

use OmniSynapse\CoreService\Request\Offer\Point;
use Tests\TestCase;
use Faker\Factory as Faker;

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
