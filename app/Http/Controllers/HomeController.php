<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Http\Response;
use App\Repositories\RoleRepository;
use App\Repositories\OfferRepository;


class HomeController extends Controller
{
    /**
     * @param RoleRepository $roleRepository
     * @param OfferRepository $offerRepository
     * @param Authenticatable $authUser
     *
     * @return Response
     */
    public function index(
        RoleRepository $roleRepository,
        OfferRepository $offerRepository,
        Authenticatable $authUser
    ): Response
    {
        if ($authUser instanceof \App\Models\Operator) {
            return \response()->render('operator', []);
        }

        $table = null;

        if ($this->user()->isAdmin()) {
            $table = $this->getAdminStatistic($roleRepository);
        } elseif ($this->user()->isAgent()) {
            $table = $this->getAgentStatistic($offerRepository);
        }

        return \response()->render('home', ['table' => $table]);
    }

    /**
     * @param RoleRepository $roleRepository
     *
     * @return Collection
     */
    protected function getAdminStatistic(RoleRepository $roleRepository): Collection
    {
        $users = $roleRepository->scopeQuery(function (Role $query) {
            return $query->join('users_roles', 'users_roles.role_id', 'roles.id');
        })
            ->get(['name'])
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
                ->get();
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
