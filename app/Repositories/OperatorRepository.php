<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface OperatorRepository
 * @package namespace App\Repositories;
 */
interface OperatorRepository extends RepositoryInterface
{
    public function model(): string;
}
