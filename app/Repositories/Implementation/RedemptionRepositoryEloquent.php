<?php

namespace App\Repositories\Implementation;

use App\Models\NauModels\Redemption;
use App\Repositories\RedemptionRepository;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class RedemptionRepositoryEloquent
 * NS: App\Repositories
 *
 * @property Redemption $model
 */
class RedemptionRepositoryEloquent extends BaseRepository implements RedemptionRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Redemption::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param array $offersId
     *
     * @return Collection
     */
    public function findForOffers(array $offersId): Collection
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->whereIn('offer_id', $offersId)->get();
        $this->resetModel();

        return $this->parserResult($model);
    }
}
