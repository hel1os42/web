<?php

namespace App\Repositories\Implementation;

use App\Models\NauModels\Account;
use App\Models\Traits\HasNau;
use App\Models\User;
use App\Repositories\AccountRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class AccountRepositoryEloquent
 * @package namespace App\Repositories;
 *
 * @property Account $model
 */
class AccountRepositoryEloquent extends BaseRepository implements AccountRepository
{
    use HasNau;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Account::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function findByAddressOrFail($address): Account
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->byAddress($address)->firstOrFail();
        $this->resetModel();

        return $this->parserResult($model);
    }

    public function existsByAddressAndOwner(string $address, User $user): bool
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->byAddress($address)->byOwner($user);
        $this->resetModel();

        return $model->exists();
    }

    public function existsByAddress(string $address): bool
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->byAddress($address);
        $this->resetModel();

        return $model->exists();
    }

    public function existsByAddressAndBalanceGreaterThan(string $address, float $amount): bool
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->byAddress($address)->where('amount', '>=', $this->convertFloatToInt($amount));
        $this->resetModel();

        return $model->exists();
    }

    /**
     * @param array $userIdsArray
     *
     * @return \Illuminate\Support\Collection|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function findAndSortByOwnerIds(array $userIdsArray): \Illuminate\Support\Collection
    {
        $this->applyCriteria();
        $this->applyScope();
        $result = $this->model->byOwners($userIdsArray)->orderBy('owner_id')->get();
        $this->resetModel();

        return $this->parserResult($result);
    }
}
