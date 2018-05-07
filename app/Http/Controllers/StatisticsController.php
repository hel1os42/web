<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\RoleRepository;
use App\Repositories\OfferRepository;

class StatisticsController extends Controller
{
    /**
     * @param RoleRepository $roleRepository
     * @param OfferRepository $offerRepository
     *
     * @return Response
     */
    public function index(
        RoleRepository $roleRepository,
        OfferRepository $offerRepository
    ): Response
    {
        $statistics = null;

        if ($this->user()->isAdmin()) {
            $statistics = $this->getAdminStatistic($roleRepository);
        } elseif ($this->user()->isAgent()) {
            $statistics = $this->getAgentStatistic($offerRepository);
        }

        return \response()->render('statistics', ['data' => $statistics]);
    }

    /**
     * @param RoleRepository $roleRepository
     *
     * @return Collection
     */
    protected function getAdminStatistic(RoleRepository $roleRepository): Collection
    {
        $users = $roleRepository->scopeQuery(function (Role $query) {
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
        return collect(['users_all' => $this->user()->count()])
            ->merge($users);
    }

    /**
     * @param OfferRepository $offerRepository
     *
     * @return array
     */
    protected function getAgentStatistic(OfferRepository $offerRepository): array
    {
        $users = $this->user()->children;

        $offersByUser = $users->map(function (User $user) use ($offerRepository) {
            return $offerRepository
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
            'advertisers'           => $users->count(),
            'advertisers_approved'  => $users->where('approved', true)->count(),
            'offers'                => $offersCount['offers'],
            'redemptions'           => $offersCount['redemptions']
        ];
    }
}
