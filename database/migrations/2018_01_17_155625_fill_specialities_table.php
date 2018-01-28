<?php

use Illuminate\Support\Collection;
use Illuminate\Database\Migrations\Migration;

class FillSpecialitiesTable extends Migration
{
    const SLUG_PREFIX = 'spec';

    /**
     * @throws InvalidArgumentException
     */
    public function up()
    {
        $specialities     = \App\Helpers\Specialities::ALL;
        $this->connection = app('db')->connection();

        logger()->info("Fill specialities: start");

        foreach ($specialities as $topCategoryName => $specs) {

            logger()->info(sprintf('Processing retail types for category: "%s"', $topCategoryName));
            $retailTypes = $this->getRetailTypes($topCategoryName);

            if (!$retailTypes->count() > 0) {
                logger()->notice(sprintf('No retail types found for "%s". Skipping...', $topCategoryName));
                continue;
            }

            foreach ($retailTypes as $retailType) {
                logger()->info(sprintf('Processing retail type "%s:"', $retailType->name));
                $this->storeRecursive($retailType, $specs);
            }
        }
        logger()->info("Fill specialities: finish");
    }

    private function storeRecursive($retailType, $specs, $group = null)
    {
        foreach ($specs as $speciality) {
            if (is_array($speciality)) {
                $this->storeRecursive($retailType, $speciality, ++$group);
                continue;
            }

            $storedSlugs = $this->getStoredSlugs($retailType->id);

            $slug = self::SLUG_PREFIX . \App\Helpers\Constants::SLUG_SEPARATOR
                    . str_slug($speciality, \App\Helpers\Constants::SLUG_SEPARATOR);

            if ($storedSlugs->isNotEmpty() && $storedSlugs->contains($slug)) {
                logger()->notice(sprintf('Speciality %s already exists. Skipping...', $speciality));
                continue;
            }

            if (true === $this->store($retailType->id, $slug, $speciality, $group)) {
                logger()->info(sprintf('Speciality "%s" saved successfully.', $speciality));
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
            logger()->error(sprintf('Can\'t save "%s" speciality.', $name));
            logger()->error(sprintf('Error msg: %s', $exception->getMessage()));

            return false;
        }

        return true;
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
}
