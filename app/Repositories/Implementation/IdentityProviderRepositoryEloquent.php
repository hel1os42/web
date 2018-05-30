<?php

namespace App\Repositories\Implementation;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\IdentityProviderRepository;
use App\Models\IdentityProvider;

/**
 * Class IdentityProviderRepositoryRepositoryEloquent
 * @package namespace App\Repositories;
 */
class IdentityProviderRepositoryEloquent extends BaseRepository implements IdentityProviderRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return IdentityProvider::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
