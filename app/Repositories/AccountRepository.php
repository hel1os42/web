<?php

namespace App\Repositories;

use App\Models\NauModels\Account;
use App\Models\User;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface AccountRepository
 * @package namespace App\Repositories;
 *
 * @method Account|null|mixed findWhere(array $where, $columns = ['*'])
 */
interface AccountRepository extends RepositoryInterface
{
    public function model(): string;

    public function findByAddressOrFail($source): Account;

    public function existsByAddressAndOwner(string $address, User $user): bool;

    public function existsByAddressAndBalanceGreaterThan(string $address, float $amount): bool;

    public function findAndSortByOwnerIds(array $userIdsArray): Collection;

    public function existsByAddress(string $address): bool;
}
