<?php

namespace App\Repositories;

use App\Models\NauModels\Account;
use App\Models\NauModels\Offer;
use App\Models\User;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface OfferRepository
 * @package namespace App\Repositories;
 *
 * @method Offer find($id, $columns = ['*'])
 */
interface OfferRepository extends RepositoryInterface
{
    public function model(): string;

    public function createForAccountOrFail(array $attributes, Account $account): Offer;

    public function findByIdAndOwner(string $id, User $user): ?Offer;
}
