<?php

use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;

/**
 * Don't use for more than 2 dimensional arrays.
 *
 * Class CategoriesTableSeeder
 */
class CategoriesTableSeeder extends Seeder
{
    private $categories = \App\Helpers\RetailTypes::ALL;

    const FOOD_DRINKS = 'Food & Drinks';
    const BEAUTY_FITNESS = 'Beauty & Fitness';
    const RETAIL_SERVICES = 'Retail & Services';
    const ATTRACTIONS_LEISURE = 'Accommodation & Leisure';
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

        $this->clearOutdatedCategories();

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

                $stored = $this->db
                    ->connection()
                    ->table('categories')
                    ->where('name', $categoryArray['name'])
                    ->first();

                foreach ($this->getCategories($children, (null === $stored) ? $lastCategoryId : $stored->id) as $item) {
                    yield $item;
                }
                continue;
            }

            yield array_merge($categoryArray, ['name' => $children]);
        }
    }

    private function clearOutdatedCategories()
    {
        $connection = $this->db->connection();

        $outdatedCategories = $connection
            ->table('categories')
            ->select([
                'id'
            ])
            ->whereNotNull('parent_id')
            ->whereNotIn('name', $this->getActualNames());

        if (0 === count($outdatedCategories)) {
            return;
        }

//        remove records from places_categories table
        $connection
            ->table('places_categories')
            ->whereIn('category_id', $outdatedCategories)
            ->delete();
//        remove records from categories table
        $connection
            ->table('categories')
            ->whereIn('id', $outdatedCategories)
            ->delete();
    }

    /**
     * return array
     */
    private function getActualNames(): array
    {
        $result = array();
        foreach($this->categories as $subCategories){
            $result = array_merge($result, $subCategories);
        }
        return $result;
    }
}
