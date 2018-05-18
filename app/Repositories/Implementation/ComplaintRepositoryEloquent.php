<?php

namespace App\Repositories\Implementation;

use App\Models\Complaint;
use App\Repositories\ComplaintRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ComplaintRepositoryEloquent
 * @package namespace App\Repositories;
 *
 * @property Complaint $model
 */
class ComplaintRepositoryEloquent extends BaseRepository implements ComplaintRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Complaint::class;
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
}
