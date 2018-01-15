<?php

namespace App\Helpers;

interface Tags
{
    const AMERICAN       = 'American';
    const ARABIC         = 'Arabic';
    const AFRICAN        = 'African';
    const ASIAN          = 'Asian';
    const BAR_FOOD       = 'Bar food';
    const BRAZILIAN      = 'Brazilian';
    const BURGERS        = 'Burgers';
    const CHINESE        = 'Chinese';
    const DESSERTS       = 'Desserts';
    const INDIAN         = 'Indian';
    const EUROPEAN       = 'European';
    const FAST_FOOD      = 'Fast food';
    const FRENCH         = 'French';
    const ICE_CREAM      = 'Ice cream';
    const INTERNATIONAL  = 'International';
    const JAPANESE       = 'Japanese';
    const MEDITERRANEAN  = 'Mediterranean';
    const MEXICAN        = 'Mexican';
    const MIDDLE_EASTERN = 'Middle Eastern';
    const ORGANIC        = 'Organic';
    const PIZZA          = 'Pizza';
    const RUSSIAN        = 'Russian';
    const SEAFOOD        = 'Seafood';
    const STEAKS         = 'Steaks';
    const THAI           = 'Thai';
    const VEGAN          = 'Vegan';
    const VEGETARIAN     = 'Vegetarian';
    const YOGURT         = 'Yogurt';

    const ALL = [
        \CategoriesTableSeeder::FOOD_DRINKS => [
            self::AMERICAN,
            self::ARABIC,
            self::AFRICAN,
            self::ASIAN,
            self::BAR_FOOD,
            self::BRAZILIAN,
            self::BURGERS,
            self::CHINESE,
            self::DESSERTS,
            self::INDIAN,
            self::EUROPEAN,
            self::FAST_FOOD,
            self::FRENCH,
            self::ICE_CREAM,
            self::INTERNATIONAL,
            self::JAPANESE,
            self::MEDITERRANEAN,
            self::MEXICAN,
            self::MIDDLE_EASTERN,
            self::ORGANIC,
            self::PIZZA,
            self::RUSSIAN,
            self::SEAFOOD,
            self::STEAKS,
            self::THAI,
            self::VEGAN,
            self::VEGETARIAN,
            self::YOGURT,
        ],
    ];
}
