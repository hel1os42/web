<?php

namespace App\Repositories\Implementation;

use App\Models\NauModels\Redemption;
use App\Models\User;
use App\Repositories\RedemptionRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

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
     * @param User $user
     *
     * @return Builder
     */
    public function queryByUser(User $user): Builder
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->byUser($user);
        $this->resetModel();

        return $this->parserResult($model);
    }
}
