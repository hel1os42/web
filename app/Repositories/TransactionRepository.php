<?php

namespace App\Repositories;

use App\Models\NauModels\Account;
use App\Models\NauModels\Transact;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface TransactionRepository
 * @package namespace App\Repositories;
 */
interface TransactionRepository extends RepositoryInterface
{
    public function model(): string;

    public function createWithAmountSourceDestination(float $amount, Account $sourceAccount, Account $destinationAccount, Bool $noFee): Transact;

    /**
     * @param User $user
     *
     * @return Builder|Transact[]
     */
    public function getBySenderOrRecepient(User $user): Builder;
}
