<?php

namespace App\Repositories\Implementation;

use App\Models\User;
use App\Repositories\UserRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class UserRepositoryEloquent
 * NS: App\Repositories
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function findByPhone(string $phone): ?User
    {
        return $this->findByField('phone', $phone)->first();
    }

    public function findByInvite(string $inviteCode): ?User
    {
        return $this->findByField('invite_code', $inviteCode)->first();
    }
}
