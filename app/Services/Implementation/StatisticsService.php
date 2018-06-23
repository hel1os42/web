<?php

namespace App\Services\Implementation;

use App\Criteria\Offer\AccountCriteria;
use App\Models\Role;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\OfferRepository;
use App\Repositories\RedemptionRepository;
use App\Repositories\RoleRepository;
use App\Services\StatisticsService as StatisticsServiceInterface;
use Illuminate\Support\Collection;

class StatisticsService implements StatisticsServiceInterface
{
    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * @var OfferRepository
     */
    private $offerRepository;

    /**
     * @var RedemptionRepository
     */
    private $redemptionRepository;

    /**
     * @var User
     */
    private $user;

    /**
     * StatisticsService constructor.
     *
     * @param RoleRepository       $roleRepository
     * @param OfferRepository      $offerRepository
     * @param AccountRepository    $accountRepository
     * @param RedemptionRepository $redemptionRepository
     */
    public function __construct(
        RoleRepository $roleRepository,
        OfferRepository $offerRepository,
        AccountRepository $accountRepository,
        RedemptionRepository $redemptionRepository
    )
    {
        $this->roleRepository       = $roleRepository;
        $this->offerRepository      = $offerRepository;
        $this->accountRepository    = $accountRepository;
        $this->redemptionRepository = $redemptionRepository;
    }

    /**
     * @param User $user
     * @return Collection
     */
    public function getStatisticsFor(User $user): Collection
    {
        $this->user = $user;
        $statistics = collect();

        if ($this->user->isAdmin()) {
            $statistics = $this->getAdminStatistic();

        } elseif ($this->user->isAgent()) {
            $statistics = $this->getAgentStatistic();
        }

        return $statistics;
    }

    /**
     * @return Collection
     */
    protected function getAdminStatistic(): Collection
    {
        $orderedFields = array_reverse(Role::getAllRoles());

        $users = $this->roleRepository->scopeQuery(function (Role $query) {
            return $query->join('users_roles', 'users_roles.role_id', 'roles.id');
        })
            ->all(['name'])
            ->groupBy('name')
            ->map(function ($list) {
                return $list->count();
            })
            ->sortBy(function () use ($orderedFields) {
                // return the order index by role name
                return array_search(func_get_arg(1), $orderedFields);
            })
            // make plural key names
            ->keyBy(function ($list, $key) {
                return $key . 's';
            });

        // add new item to collection head
        return collect(['users_all' => $this->user->count()])
            ->merge($users);
    }

    /**
     * @return Collection
     */
    protected function getAgentStatistic(): Collection
    {
        $childrenData = $this->user->children()->get(['id', 'approved']);
        $childrenIds  = $childrenData->pluck('id')->toArray();

        $accounts   = $this->accountRepository->findWhereIn('owner_id', $childrenIds, ['id']);
        $accountIds = $accounts->pluck('id')->toArray();

        $offerIds = $this->offerRepository
            ->pushCriteria(new AccountCriteria($accountIds))
            ->withoutGlobalScopes()
            ->pluck('id');

        $redemptionsCount = $this->redemptionRepository
            ->scopeQuery(function ($query) use ($offerIds) {
                return $query->whereIn('offer_id', $offerIds);
            })
            ->count();

        return collect([
            'advertisers'          => $childrenData->count(),
            'advertisers_approved' => $childrenData->where('approved', true)->count(),
            'offers'               => $offerIds->count(),
            'redemptions'          => $redemptionsCount
        ]);
    }
}
