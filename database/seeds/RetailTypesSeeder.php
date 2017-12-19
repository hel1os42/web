<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;

class RetailTypesSeeder extends Seeder
{
    /**
     * @var \Illuminate\Database\Connection $connection
     */
    private $connection;

    public function __construct(DatabaseManager $database)
    {
        $this->connection = $database->connection();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function run()
    {
        $retailTypes = \App\Helpers\RetailTypes::ALL;

        foreach ($retailTypes as $categoryName => $categoryRetailTypes) {

            $this->command->info(sprintf('Processing category: "%s"', $categoryName));
            $category = $this->getCategory($categoryName);
            if (null === $category) {
                $this->command->error(sprintf('Category "%s" not found in DB. Skipping...', $categoryName));
                continue;
            }

            $storedSlugs = $this->getStoredSlugs($category->id);

            foreach ($categoryRetailTypes as $retailType) {

                $slug = str_slug($retailType, \App\Helpers\Constants::SLUG_SEPARATOR);

                if ($storedSlugs->isNotEmpty() && $storedSlugs->contains($slug)) {
                    $this->command->warn(sprintf('Retail type %s already exists. Skipping...', $retailType));
                    continue;
                }

                if (true === $this->storeRetailType($category->id, $slug, $retailType)) {
                    $this->command->info(sprintf('Retail type "%s" saved successfully.', $retailType));
                }
            }
        }
    }

    /**
     * @param string $name
     *
     * @return null|StdClass
     * @throws InvalidArgumentException
     */
    private function getCategory(string $name): ?StdClass
    {
        return $this->connection
            ->table('categories')
            ->where('name', $name)
            ->first();
    }

    /**
     * @param string $categoryId
     *
     * @return Collection|null
     * @throws InvalidArgumentException
     */
    private function getStoredSlugs(string $categoryId): ?Collection
    {
        return $this->connection
            ->table('retail_types')
            ->where('category_id', $categoryId)
            ->get()
            ->pluck('slug');
    }

    /**
     * @param string $categoryId
     * @param string $slug
     * @param string $name
     *
     * @return bool
     */
    private function storeRetailType(string $categoryId, string $slug, string $name)
    {
        try {
            $this->connection
                ->table('retail_types')
                ->insert([
                    'category_id' => $categoryId,
                    'slug'        => $slug,
                    'name'        => $name
                ]);
        } catch (Exception $exception) {
            $this->command->error(sprintf('Can\'t save "%s" retail type.', $name, $exception->getMessage()));
            $this->command->comment(sprintf('error msg: %s', $exception->getMessage()));

            return false;
        }

        return true;
    }
}
