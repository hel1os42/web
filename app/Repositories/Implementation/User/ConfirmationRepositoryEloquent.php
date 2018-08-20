<?php

namespace App\Repositories\Implementation\User;

use App\Models\User\Confirmation;
use App\Repositories\User\ConfirmationRepository;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ConfirmationRepositoryEloquent
 * NS: App\Repositories\Implementation\User
 *
 * @property Confirmation $model
 */
class ConfirmationRepositoryEloquent extends BaseRepository implements ConfirmationRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Confirmation::class;
    }
}
