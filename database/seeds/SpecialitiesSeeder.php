<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;

class SpecialitiesSeeder extends Seeder
{
    const SLUG_PREFIX = 'spec';
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
        $specialities = \App\Helpers\Specialities::ALL;

        foreach ($specialities as $topCategoryName => $specs) {


            $this->command->line(sprintf('Processing retail types for category: "%s"', $topCategoryName));
            $retailTypes = $this->getRetailTypes($topCategoryName);

            if (!$retailTypes->count() > 0) {
                $this->command->error(sprintf('No retail types found for "%s". Skipping...', $topCategoryName));
                continue;
            }

            foreach ($retailTypes as $retailType) {
                $this->command->line(sprintf('Processing retail type "%s:"', $retailType->name));
                $this->www($retailType, $specs);
            }
        }
    }

    private function www($retailType, $specs, $group = null)
    {
        foreach ($specs as $speciality) {
            if (is_array($speciality)) {
                $this->www($retailType, $speciality, ++$group);
                continue;
            }

            $storedSlugs = $this->getStoredSlugs($retailType->id);

            $slug = self::SLUG_PREFIX . \App\Helpers\Constants::SLUG_SEPARATOR
                    . str_slug($speciality, \App\Helpers\Constants::SLUG_SEPARATOR);

            if ($storedSlugs->isNotEmpty() && $storedSlugs->contains($slug)) {
                $this->command->warn(sprintf('Speciality %s already exists. Skipping...', $speciality));
                continue;
            }

            if (true === $this->store($retailType->id, $slug, $speciality, $group)) {
                $this->command->info(sprintf('Speciality "%s" saved successfully.', $speciality));
            }
        }
    }

    /**
     * @param string $topCategoryName
     *
     * @return Collection
     * @throws InvalidArgumentException
     */
    private function getRetailTypes(string $topCategoryName): Collection
    {
        return $this->connection
            ->table('categories as tbl1')
            ->select('tbl1.*')
            ->leftJoin('categories as tbl2', 'tbl2.id', '=', 'tbl1.parent_id')
            ->where('tbl2.name', $topCategoryName)
            ->get();
    }

    /**
     * @param string $retailTypeId
     *
     * @return Collection
     * @throws InvalidArgumentException
     */
    private function getStoredSlugs(string $retailTypeId): Collection
    {
        return $this->connection
            ->table('specialities')
            ->where('retail_type_id', $retailTypeId)
            ->get()
            ->pluck('slug');
    }

    /**
     * @param string   $categoryId
     * @param string   $slug
     * @param string   $name
     * @param int|null $group
     *
     * @return bool
     */
    private function store(string $categoryId, string $slug, string $name, int $group = null)
    {
        try {
            $this->connection
                ->table('specialities')
                ->insert([
                    'retail_type_id' => $categoryId,
                    'slug'           => $slug,
                    'name'           => $name,
                    'group'          => $group
                ]);
        } catch (Exception $exception) {
            $this->command->error(sprintf('Can\'t save "%s" speciality.', $name, $exception->getMessage()));
            $this->command->comment(sprintf('Error msg: %s', $exception->getMessage()));

            return false;
        }

        return true;
    }
}
