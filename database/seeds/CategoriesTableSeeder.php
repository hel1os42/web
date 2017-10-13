<?php

use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;

class CategoriesTableSeeder extends Seeder
{
    private $categories = [
        self::FOOD_DRINKS => [
            'American',
            'Arabic',
            'African',
            'Asian',
            'Bar food',
            'Brazilian',
            'Burgers',
            'Chinese',
            'Desserts',
            'Indian',
            'European',
            'Fast food',
            'French',
            'Ice cream',
            'International',
            'Japanese',
            'Mediterranean',
            'Mexican',
            'Middle Eastern',
            'Organic',
            'Pizza',
            'Russian',
            'Seafood',
            'Steaks',
            'Thai',
            'Vegan',
            'Vegetarian',
            'Yogurt',
        ],
        self::BEAUTY_FITNESS,
        self::RETAIL_SERVICES,
        self::ATTRACTIONS_LEISURE,
        self::OTHER_ONLINE
    ];

    const FOOD_DRINKS = 'Food & Drinks';
    const BEAUTY_FITNESS = 'Beauty & Fitness';
    const RETAIL_SERVICES = 'Retail & Services';
    const ATTRACTIONS_LEISURE = 'Attractions & Leisure';
    const OTHER_ONLINE = 'Other & Online';

    /**
     * @var DatabaseManager
     */
    private $db;

    public function __construct(DatabaseManager $database)
    {
        $this->db = $database;
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = $this->getCategories();

        $connection = $this->db->connection();

        foreach ($categories as $category) {
            if ($connection->table('categories')->where('name', $category['name'])->exists()) {
                printf('Category %s already exists. Skipping...' . PHP_EOL, $category['name']);
                continue;
            }

            $connection->table('categories')->insert($category);
        }
    }

    private function getCategories(array $categories = null, string $parent = null): iterable
    {
        if (null === $categories) {
            $categories = $this->categories;
        }

        $lastCategoryId = null;
        foreach ($categories as $category => $children) {
            $lastCategoryId = Uuid::generate(4)->__toString();

            $categoryArray = [
                'id'         => $lastCategoryId,
                'name'       => $category,
                'created_at' => Carbon::now()->__toString(),
                'updated_at' => Carbon::now()->__toString()
            ];

            if (null !== $parent) {
                $categoryArray['parent_id'] = $parent;
            }

            if (is_array($children)) {
                yield $categoryArray;

                foreach ($this->getCategories($children, $lastCategoryId) as $item) {
                    yield $item;
                }
                continue;
            }

            yield array_merge($categoryArray, ['name' => $children]);
        }
    }
}
