<?php

use Illuminate\Support\Collection;
use Illuminate\Database\Migrations\Migration;

class FillTagsTable extends Migration
{
    const SLUG_PREFIX = 'tag';

    /**
     * @throws InvalidArgumentException
     */
    public function up()
    {
        $tags             = \App\Helpers\Tags::ALL;
        $this->connection = app('db')->connection();

        foreach ($tags as $categoryName => $categoryTags) {

            printf('Processing category: "%s"', $categoryName);
            $category = $this->getCategory($categoryName);
            if (null === $category) {
                printf('Category "%s" not found in DB. Skipping...' . PHP_EOL, $categoryName);
                continue;
            }

            $storedSlugs = $this->getStoredSlugs($category->id);

            foreach ($categoryTags as $tag) {

                $slug = self::SLUG_PREFIX . \App\Helpers\Constants::SLUG_SEPARATOR
                        . str_slug($tag, \App\Helpers\Constants::SLUG_SEPARATOR);

                if ($storedSlugs->isNotEmpty() && $storedSlugs->contains($slug)) {
                    printf('Tag %s already exists. Skipping...' . PHP_EOL, $tag);
                    continue;
                }

                if (true === $this->store($category->id, $slug, $tag)) {
                    printf('Tag "%s" saved successfully.' . PHP_EOL, $tag);
                }
            }
        }
    }

    /**
     * @param string $name
     *
     * @return null|stdClass
     */
    private function getCategory(string $name): ?stdClass

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
            ->table('tags')
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
    private function store(string $categoryId, string $slug, string $name)
    {
        try {
            $this->connection
                ->table('tags')
                ->insert([
                    'category_id' => $categoryId,
                    'slug'        => $slug,
                    'name'        => $name
                ]);
        } catch (Exception $exception) {
            printf('Can\'t save "%s" tag.' . PHP_EOL, $name, $exception->getMessage());
            printf('error msg: %s' . PHP_EOL, $exception->getMessage());

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
