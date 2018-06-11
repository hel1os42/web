<?php

namespace App\Services\Implementation;

use App\Models\Role;
use App\Models\User;
use App\Repositories\OfferRepository;
use App\Repositories\RoleRepository;
use App\Services\StatisticsService as StatisticsServiceInterface;
use Illuminate\Support\Collection;

class StatisticsService implements StatisticsServiceInterface
{
    private $roleRepository;
    private $offerRepository;
    private $user;

    public function __construct(
        RoleRepository $roleRepository,
        OfferRepository $offerRepository
    ) {
        $this->roleRepository  = $roleRepository;
        $this->offerRepository = $offerRepository;
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
            ->sortBy(function($count, $role) use ($orderedFields) {
                return array_search($role, $orderedFields);
            })
            // make plural key names
            ->keyBy(function($list, $key) {
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
        $users = $this->user->children;

        $offersByUser = $users->map(function (User $user) {
            return $this->offerRepository
                ->scopeAccount($user->getAccountForNau())
                ->withoutGlobalScopes()
                ->withCount(['redemptions'])
                ->all();
        });

        $offersCount = $offersByUser->reduce(function ($count, $item) {
            $count['offers']      += $item->count();
            $count['redemptions'] += $item->pluck('redemptions_count')->sum();
            return $count;
        }, ['offers' => 0, 'redemptions' => 0]);

        return collect([
            'advertisers'          => $users->count(),
            'advertisers_approved' => $users->where('approved', true)->count(),
            'offers'               => $offersCount['offers'],
            'redemptions'          => $offersCount['redemptions']
        ]);
    }
}
