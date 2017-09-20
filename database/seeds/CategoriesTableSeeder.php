<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;

class CategoriesTableSeeder extends Seeder
{
    private $categories = [
        'Food & Drinks' => [
            'Food'   => [
                'Healthy food',
                'Meal food',
                'Vegetarian cuisine',
                'Italian cuisine',
                'Indian cuisine',
                'Mexican cuisine',
                'BBQ Grill',
            ],
            'Drinks' => [
                'Alcoholic',
                'Non alcoholic',
            ]
        ],
        'Beauty & Fitness',
        'Retail & Services',
        'Attractions & Leisure',
        'Other & Online'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = $this->getCategories();
        foreach ($categories as $category) {
            \Illuminate\Support\Facades\DB::table('categories')->insert($category);
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
