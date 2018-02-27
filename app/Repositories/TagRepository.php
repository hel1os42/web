<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface TagRepositoryRepository
 * @package App\Repositories
 */
interface TagRepository extends RepositoryInterface
{
    public function model(): string;

    public function findIdsByCategoryAndSlugs(string $categoryId, array $slugs): array;
}
