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

    public function __construct(
        RoleRepository $roleRepository,
        OfferRepository $offerRepository
    ) {
        $this->roleRepository  = $roleRepository;
        $this->offerRepository = $offerRepository;
    }

    /**
     * @param User $user must be admin
     *
     * @return Collection
     */
    public function getAdminStatistic(User $user): Collection
    {
        $users = $this->roleRepository->scopeQuery(function (Role $query) {
            $orderedFields = array_reverse($query->getAllRoles());

            return $query->join('users_roles', 'users_roles.role_id', 'roles.id')
                ->orderByRaw(sprintf("FIELD(name, '%s')", implode("', '", $orderedFields)));
        })
            ->all(['name'])
            ->groupBy('name')
            ->map(function ($list) {
                return $list->count();
            })
            // make plural key names
            ->keyBy(function($list, $key) {
                return $key . 's';
            });

        // add new item to collection head
        return collect(['users_all' => $user->count()])
            ->merge($users);
    }

    /**
     * @param User $user must be agent
     *
     * @return array
     */
    public function getAgentStatistic(User $user): array
    {
        $users = $user->children;

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

        return [
            'advertisers'          => $users->count(),
            'advertisers_approved' => $users->where('approved', true)->count(),
            'offers'               => $offersCount['offers'],
            'redemptions'          => $offersCount['redemptions']
        ];
    }
}
