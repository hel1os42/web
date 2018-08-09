<?php

namespace App\Repositories\Implementation;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Criteria\UserRequestCriteria;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Events\RepositoryEntityCreated;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class UserRepositoryEloquent
 * NS: App\Repositories
 *
 * @property User $model
 *
 * @method User setApproved(bool $approve)
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'              => 'like',
        'phone'             => 'like',
        'email'             => 'like',
        'place.name'        => 'like',
        'place.description' => 'like',
        'roles.name'        => 'in',
        'accounts.address'  => '=',
        'updated_at'        => '=',
    ];

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
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(UserRequestCriteria::class));
    }

    public function findByPhone(string $phone): ?User
    {
        return $this->findByField('phone', $phone)->first();
    }

    public function findByInvite(string $inviteCode): ?User
    {
        return $this->findByField('invite_code', $inviteCode)->first();
    }

    /**
     * @param array $attributes
     * @return User
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function create(array $attributes): User
    {
        if (!is_null($this->validator)) {
            // we should pass data that has been casts by the model
            // to make sure data type are same because validator may need to use
            // this data to compare with data that fetch from database.
            $attributes = $this->model->newInstance()->forceFill($attributes)->toArray();

            $this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_CREATE);
        }

        $model = $this->model->newInstance($attributes);
        $model->referrer()->associate($attributes['referrer_id']);
        $model->save();
        $this->resetModel();

        event(new RepositoryEntityCreated($this, $model));

        return $this->parserResult($model);
    }

    /**
     * @param User $user
     *
     * @return Builder
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getChildrenByUser(User $user): Builder
    {
        $this->model = $user->children();

        $this->applyCriteria();
        $this->applyScope();

        $model = $this->model->getQuery();

        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * @param mixed $childrenIds
     * @return UserRepository
     */
    public function getChildrenByUsers($childrenIds): UserRepository
    {
        $this->model = $this->model->join('users_parents', 'users.id', 'users_parents.user_id')
            ->whereIn('users_parents.parent_id', $childrenIds);

        return $this;
    }
}
