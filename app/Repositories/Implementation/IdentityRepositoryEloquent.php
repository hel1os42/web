<?php

namespace App\Repositories\Implementation;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\IdentityRepository;
use App\Models\Identity;

/**
 * Class IdentityRepositoryEloquent
 * @package namespace App\Repositories;
 */
class IdentityRepositoryEloquent extends BaseRepository implements IdentityRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Identity::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
