<?php

namespace App\Helpers;

interface Specialities
{
    const ALCOHOL               = 'Alcohol';
    const BIG_SCREEN            = 'Big Screen';
    const CARDS_ACCEPTED        = 'Cards Accepted';
    const KIDS_WELCOME          = 'Kids Welcome';
    const KIDS_ROOM             = 'Kids Room';
    const LIVE_MUSIC            = 'Live Music';
    const OUTDOOR_AVAILABLE     = 'Outdoor Available';
    const OUTDOOR_HEATING       = 'Outdoor Heating';
    const PARKING               = 'Parking';
    const PETS_WELCOME          = 'Pets Welcome';
    const PRICE_LEVEL_LOW       = 'Price Level: Low';
    const PRICE_LEVEL_MEDIUM    = 'Price Level: Medium';
    const PRICE_LEVEL_HIGH      = 'Price Level: High';
    const RESERVATION_ONLY      = 'Reservation Only';
    const SMOKING_INDOOR        = 'Smoking Indoor';
    const SHISHA                = 'Shisha';
    const TAKEAWAY              = 'Takeaway';
    const WHEELCHAIR_ACCESSIBLE = 'Wheelchair Accessible';
    const WI_FI                 = 'Wi-Fi';
    const DELIVERY              = 'Delivery';
    const BAR_AND_DRINKS        = 'Bar and Drinks';
    const CERTIFIED             = 'Certified';
    const FEMALE_ONLY           = 'Female Only';
    const MALE_ONLY             = 'Male Only';
    const APPOINTMENT_ONLY      = 'Appointment Only';
    const AGE_LIMITS            = 'Age Limits';
    const INDOOR_ACTIVITIES     = 'Indoor Activities';
    const OUTDOOR_ACTIVITIES    = 'Outdoor Activities';
    const LIVE_ENTERTAINMENT    = 'Live Entertainment';
    const CASH_ON_DELIVERY      = 'Cash on Delivery';

    const ALL = [
        \CategoriesTableSeeder::FOOD_DRINKS         => [
            self::ALCOHOL,
            self::BIG_SCREEN,
            self::CARDS_ACCEPTED,
            self::KIDS_WELCOME,
            self::KIDS_ROOM,
            self::LIVE_MUSIC,
            self::OUTDOOR_AVAILABLE,
            self::OUTDOOR_HEATING,
            self::PARKING,
            self::PETS_WELCOME,
            self::RESERVATION_ONLY,
            self::SMOKING_INDOOR,
            self::SHISHA,
            self::TAKEAWAY,
            self::WHEELCHAIR_ACCESSIBLE,
            self::WI_FI,
            self::DELIVERY,
            [
                self::PRICE_LEVEL_LOW,
                self::PRICE_LEVEL_MEDIUM,
                self::PRICE_LEVEL_HIGH,
            ],
        ],
        \CategoriesTableSeeder::BEAUTY_FITNESS      => [
            self::APPOINTMENT_ONLY,
            self::BAR_AND_DRINKS,
            self::CARDS_ACCEPTED,
            self::CERTIFIED,
            self::PARKING,
            [
                self::FEMALE_ONLY,
                self::MALE_ONLY,
            ],
            [
                self::PRICE_LEVEL_LOW,
                self::PRICE_LEVEL_MEDIUM,
                self::PRICE_LEVEL_HIGH,
            ],
        ],
        \CategoriesTableSeeder::RETAIL_SERVICES     => [
            self::APPOINTMENT_ONLY,
            self::DELIVERY,
            self::CARDS_ACCEPTED,
            [
                self::PRICE_LEVEL_LOW,
                self::PRICE_LEVEL_MEDIUM,
                self::PRICE_LEVEL_HIGH,
            ],
        ],
        \CategoriesTableSeeder::ATTRACTIONS_LEISURE => [
            self::AGE_LIMITS,
            self::ALCOHOL,
            self::BAR_AND_DRINKS,
            self::APPOINTMENT_ONLY,
            self::CARDS_ACCEPTED,
            self::INDOOR_ACTIVITIES,
            self::OUTDOOR_ACTIVITIES,
            self::KIDS_WELCOME,
            self::LIVE_ENTERTAINMENT,
            [
                self::PRICE_LEVEL_LOW,
                self::PRICE_LEVEL_MEDIUM,
                self::PRICE_LEVEL_HIGH,
            ],
        ],
        \CategoriesTableSeeder::OTHER_ONLINE        => [
            self::CASH_ON_DELIVERY,
            self::CARDS_ACCEPTED,
            [
                self::PRICE_LEVEL_LOW,
                self::PRICE_LEVEL_MEDIUM,
                self::PRICE_LEVEL_HIGH,
            ],
        ],
    ];
}
