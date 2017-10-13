<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 09.10.17
 * Time: 4:24
 */

namespace App\Helpers;

interface RetailTypes
{
    const RESTAURANTS               = 'Restaurants';
    const CAFES                     = 'Cafes';
    const BARS                      = 'Bars';
    const INFORMAL_TAKEAWAY         = 'Informal & Takeaway';
    const SPAS                      = 'SPAs';
    const BEAUTY_SALONS             = 'Beauty salons';
    const HAIR_NAILS                = 'Hair & nails';
    const COSMETOLOGY_WELLNESS      = 'Cosmetology & Wellness';
    const FITNESS_CENTERS           = 'Fitness centers';
    const MEN_S_GROOMING            = 'Men’s grooming';
    const GROCERY_SHOPS             = 'Grocery shops';
    const FASHION_RETAIL            = 'Fashion retail';
    const BEAUTY_GOODS              = 'Beauty goods';
    const CHILDREN_GOODS            = 'Children goods';
    const INTERIOR_AND_FURNITURE    = 'Interior and furniture';
    const SPORTS_AND_LEISURE_RETAIL = 'Sports and leisure retail';
    const ELECTRONICS_SHOPS         = 'Electronics shops';
    const HARDWARE_SHOPS            = 'Hardware shops';
    const JEWELERY_SALONS           = 'Jewelery salons';
    const OPTICIAN_SALONS           = 'Optician salons';
    const CAR_SERVICES              = 'Car services';
    const EDUCATION_SERVICES        = 'Education services';
    const TELECOMMUNICATION         = 'Telecommunication';
    const CLEANING_SERVICES         = 'Cleaning services';
    const MAINTENANCE_SERVICES      = 'Maintenance services';
    const PETS_SERVICES             = 'Pets services';
    const PHOTOGRAPHY_SERVICES      = 'Photography services';
    const OTHER_SERVICES            = 'Other services';
    const ATTRACTIONS               = 'Attractions';
    const SPORTS                    = 'Sports';
    const CINEMA                    = 'Cinema';
    const NIGHTCLUBS                = 'Nightclubs';
    const EVENTS_AND_MASTERCLASSES  = 'Events and masterclasses';
    const CONCERTS_AND_PERFORMANCE  = 'Concerts and performance';
    const OTHERS                    = 'Others';
    const ONLINE_SHOPS              = 'Online shops';
    const ONLINE_SERVICES           = 'Online services';
    const MISCELLANEOUS             = 'Miscellaneous';

    const ALL = [
        \CategoriesTableSeeder::FOOD_DRINKS         => [
            self::RESTAURANTS,
            self::CAFES,
            self::BARS,
            self::INFORMAL_TAKEAWAY,
        ],
        \CategoriesTableSeeder::BEAUTY_FITNESS      => [
            self::SPAS,
            self::BEAUTY_SALONS,
            self::HAIR_NAILS,
            self::COSMETOLOGY_WELLNESS,
            self::FITNESS_CENTERS,
            self::MEN_S_GROOMING,
        ],
        \CategoriesTableSeeder::RETAIL_SERVICES     => [
            self::GROCERY_SHOPS,
            self::FASHION_RETAIL,
            self::BEAUTY_GOODS,
            self::CHILDREN_GOODS,
            self::INTERIOR_AND_FURNITURE,
            self::SPORTS_AND_LEISURE_RETAIL,
            self::ELECTRONICS_SHOPS,
            self::HARDWARE_SHOPS,
            self::JEWELERY_SALONS,
            self::OPTICIAN_SALONS,
            self::CAR_SERVICES,
            self::EDUCATION_SERVICES,
            self::TELECOMMUNICATION,
            self::CLEANING_SERVICES,
            self::MAINTENANCE_SERVICES,
            self::PETS_SERVICES,
            self::PHOTOGRAPHY_SERVICES,
            self::OTHER_SERVICES,
        ],
        \CategoriesTableSeeder::ATTRACTIONS_LEISURE => [
            self::ATTRACTIONS,
            self::SPORTS,
            self::CINEMA,
            self::NIGHTCLUBS,
            self::EVENTS_AND_MASTERCLASSES,
            self::CONCERTS_AND_PERFORMANCE,
            self::OTHERS,
        ],
        \CategoriesTableSeeder::OTHER_ONLINE        => [
            self::ONLINE_SHOPS,
            self::ONLINE_SERVICES,
            self::MISCELLANEOUS,
        ],
    ];
}
