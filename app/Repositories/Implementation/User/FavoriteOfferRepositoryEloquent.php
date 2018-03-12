<?php

namespace App\Repositories\Implementation\User;

use App\Models\User;
use App\Models\User\FavoriteOffers;
use App\Repositories\User\FavoriteOfferRepository;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class FavoriteOfferRepositoryEloquent
 * NS: App\Repositories\User
 *
 * @property FavoriteOffers $model
 */
class FavoriteOfferRepositoryEloquent extends BaseRepository implements FavoriteOfferRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return FavoriteOffers::class;
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
     * @param User $user
     *
     * @return Builder
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getByUser(User $user): Builder
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->byUser($user);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * @param User $user
     *
     * @return Builder
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function findByUser(User $user): Builder
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->byUser($user);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * @param string $userId
     * @param string $offerId
     *
     * @return FavoriteOffers
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function findByUserIdAndOfferId(string $userId, string $offerId): FavoriteOffers
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->where(['user_id' => $userId, 'offer_id' =>$offerId])->firstOrFail();
        $this->resetModel();

        return $this->parserResult($model);
    }
}
