<?php

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;
use Webpatser\Uuid\Uuid;

class FillCategoriesTable extends Migration
{
    private $categories = \App\Helpers\RetailTypes::ALL;

    const FOOD_DRINKS           = 'Food & Drinks';
    const BEAUTY_FITNESS        = 'Beauty & Fitness';
    const RETAIL_SERVICES       = 'Retail & Services';
    const ATTRACTIONS_LEISURE   = 'Attractions & Leisure';
    const OTHER_ONLINE          = 'Other & Online';

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * @throws InvalidArgumentException
     */
    public function up()
    {
        $this->db = app('db');

        $categories = $this->getCategories();

        $connection = $this->db->connection();

        $this->clearOutdatedCategories();
        logger()->info("Fill categories: start");
        foreach ($categories as $category) {
            if ($connection->table('categories')->where('name', $category['name'])->exists()) {
                logger()->notice(sprintf('Category %s already exists. Skipping...', $category['name']));
                continue;
            }

            $connection->table('categories')->insert($category);
        }
        logger()->info("Fill categories: finish");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
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
            ->whereNotIn('name', $this->getActualNames())
            ->pluck('id');

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
        $result = [];
        foreach ($this->categories as $subCategories) {
            $result = array_merge($result, $subCategories);
        }

        return $result;
    }
}
