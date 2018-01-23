<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;

class TagsSeeder extends Seeder
{
    const SLUG_PREFIX = 'tag';
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
        $tags = \App\Helpers\Tags::ALL;

        foreach ($tags as $categoryName => $categoryTags) {

            $this->command->info(sprintf('Processing category: "%s"', $categoryName));
            $category = $this->getCategory($categoryName);
            if (null === $category) {
                $this->command->error(sprintf('Category "%s" not found in DB. Skipping...', $categoryName));
                continue;
            }

            $storedSlugs = $this->getStoredSlugs($category->id);

            foreach ($categoryTags as $tag) {

                $slug = self::SLUG_PREFIX . \App\Helpers\Constants::SLUG_SEPARATOR
                        . str_slug($tag, \App\Helpers\Constants::SLUG_SEPARATOR);

                if ($storedSlugs->isNotEmpty() && $storedSlugs->contains($slug)) {
                    $this->command->warn(sprintf('Tag %s already exists. Skipping...', $tag));
                    continue;
                }

                if (true === $this->store($category->id, $slug, $tag)) {
                    $this->command->info(sprintf('Tag "%s" saved successfully.', $tag));
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
            $this->command->error(sprintf('Can\'t save "%s" tag.', $name, $exception->getMessage()));
            $this->command->comment(sprintf('error msg: %s', $exception->getMessage()));

            return false;
        }

        return true;
    }
}
