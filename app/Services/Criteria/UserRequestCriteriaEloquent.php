<?php

namespace App\Services\Criteria;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CriteriaInterfaceEloquent
 * @package App\Services\Criteria
 */
class UserRequestCriteriaEloquent extends RequestCriteriaEloquent implements UserRequestCriteria, CriteriaInterface
{
    protected $availableForUserId;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder|\Illuminate\Database\Eloquent\Model $model
     * @param RepositoryInterface                         $repository
     *
     * @return Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function apply($model, RepositoryInterface $repository)
    {
        parent::apply($model, $repository);
        $this->availableForUserId = $this->request->get('availableForUser');
        $this->applyFilterForUser();

        return $this->model;
    }

    /**
     * @return CriteriaInterface
     */
    protected function applyFilterForUser(): CriteriaInterface
    {
        if (null === $this->availableForUserId) {
            return $this;
        }

        if (auth()->user()->isAdmin()) {
            $this->filterForUserByAdmin();
        } elseif (auth()->user()->isAgent()) {
            $this->filterForUserByAgent();
        }

        return $this;
    }

    protected function filterForUserByAdmin()
    {
        $model = $this->model;
        $user  = $model->getModel()->find($this->availableForUserId);

        // $parent - can be only Agent in current case
        if (null !== $user && $user->isChiefAdvertiser() && null !== $parent = $user->parents()->first()) {
            $parentId = $parent->getId();
            $chiefIds = $this->getChildrenIdsByRole($parent, Role::ROLE_CHIEF_ADVERTISER);

            $model->leftJoin('users_parents', 'users.id', '=', 'users_parents.user_id')
                ->where(function(Builder $query) use ($parentId) {
                    $query->where('users_parents.parent_id', $parentId)
                        ->orWhere('users_parents.parent_id', null);
                });

            $this->excludeChildrenByParentsIds($model, $chiefIds);

            $this->model = $model;
            return;
        }

        $model->leftJoin('users_parents', 'users.id', '=', 'users_parents.user_id')
            ->where('users_parents.user_id', null);

        $this->model = $model;
    }

    protected function filterForUserByAgent()
    {
        $model = $this->model;

        $chiefIds = $this->getChildrenIdsByRole(auth()->user(), Role::ROLE_CHIEF_ADVERTISER);
        $this->excludeChildrenByParentsIds($model, $chiefIds);

        $this->model = $model;
    }

    /**
     * @param $query
     * @param array|Collection $ids
     */
    protected function excludeChildrenByParentsIds(&$query, $ids)
    {
        $query->whereNotIn('id', function(QueryBuilder $query) use ($ids) {
            $query->select('user_id')
                ->from('users_parents AS up')
                ->whereIn('up.parent_id', $ids);
        });
    }

    /**
     * @param User $parent
     * @param string $roleName
     * @return Collection
     */
    protected function getChildrenIdsByRole(User $parent, string $roleName): Collection
    {
        return $parent->children()
            ->whereHas('roles', function(Builder $query) use ($roleName) {
                $query->where('roles.name', $roleName);
            })
            ->pluck('id');
    }
}
