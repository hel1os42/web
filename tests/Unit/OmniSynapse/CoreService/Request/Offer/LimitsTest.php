<?php

namespace OmniSynapse\CoreService\Request\Offer;

use Faker\Factory as Faker;
use Tests\TestCase;

class LimitsTest extends TestCase
{
    /**
     * @return void
     */
    public function testGettersAndSetters()
    {
        $faker = Faker::create();

        $maxCount        = $faker->randomDigitNotNull;
        $perDay          = $faker->randomDigitNotNull;
        $perUser         = $faker->randomDigitNotNull;
        $perUserPerDay   = $faker->randomDigitNotNull;
        $perUserPerWeek  = $faker->randomDigitNotNull;
        $perUserPerMonth = $faker->randomDigitNotNull;
        $minLevel        = $faker->randomDigitNotNull;

        $limits = (new Limits)
            ->setMaxCount($maxCount)
            ->setPerDay($perDay)
            ->setPerUser($perUser)
            ->setPerUserPerDay($perUserPerDay)
            ->setPerUserPerWeek($perUserPerWeek)
            ->setPerUserPerMonth($perUserPerMonth)
            ->setMinLevel($minLevel);

        $this->assertEquals($maxCount, $limits->getMaxCount(), 'maxCount');
        $this->assertEquals($perDay, $limits->getPerDay(), 'perDay');
        $this->assertEquals($perUser, $limits->getPerUser(), 'perUser');
        $this->assertEquals($perUserPerDay, $limits->getPerUserPerDay(), 'perUserPerDay');
        $this->assertEquals($perUserPerWeek, $limits->getPerUserPerWeek(), 'perUserPerWeek');
        $this->assertEquals($perUserPerMonth, $limits->getPerUserPerMonth(), 'perUserPerMonth');
        $this->assertEquals($minLevel, $limits->getMinLevel(), 'minLevel');

        $expected = [
            'max_count'          => $maxCount,
            'per_day'            => $perDay,
            'per_user'           => $perUser,
            'per_user_per_day'   => $perUserPerDay,
            'per_user_per_week'  => $perUserPerWeek,
            'per_user_per_month' => $perUserPerMonth,
            'min_level'          => $minLevel,
        ];

        $this->assertEquals($expected, $limits->jsonSerialize(), 'Expected array is not equals with LIMITS array');
    }
}
