<?php

namespace App\Repositories\Implementation;

use App\Models\NauModels\Account;
use App\Models\NauModels\Transact;
use App\Models\User;
use App\Repositories\TransactionRepository;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class TransactionRepositoryEloquent
 * @package namespace App\Repositories;
 *
 * @property Transact $model
 */
class TransactionRepositoryEloquent extends BaseRepository implements TransactionRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Transact::class;
    }

    /**
     * Boot up the repository, pushing criteria
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param float   $amount
     * @param Account $sourceAccount
     * @param Account $destinationAccount
     *
     * @return Transact
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function createWithAmountSourceDestination(float $amount, Account $sourceAccount, Account $destinationAccount): Transact
    {
        $attributes = [
            'amount' => $amount,
            'source_account_id' => $sourceAccount->id,
            'destination_account_id' => $destinationAccount->id
        ];

        return $this->create($attributes);
    }

    /**
     * @param User $user
     *
     * @return Builder
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getBySenderOrRecepient(User $user): Builder
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->forUser($user);
        $this->resetModel();

        return $this->parserResult($model);
    }
}
