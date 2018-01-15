<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

/**
 * Interface SpecialityRepository
 * @package App\Repositories
 */
interface SpecialityRepository extends RepositoryInterface
{
    public function model(): string;

    public function findByRetailType(string $retailTypeId): Builder;

    public function findIdsByRetailTypeAndSlugs(string $retailTypeId, array $slugs): array;
}
