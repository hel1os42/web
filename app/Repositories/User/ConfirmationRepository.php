<?php

namespace App\Repositories\User;

use App\Models\User\Confirmation;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ConfirmationRepository
 * NS: App\Repositories\User
 *
 * @method Confirmation create(array $attributes)
 */
interface ConfirmationRepository extends RepositoryInterface
{
    public function model(): string;
}
