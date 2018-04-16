<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    /**
     * @return Response
     */
    public function index()
    {
        $table = null;
        $view = ($this->user() instanceof \App\Models\Operator) ? 'operator' : 'home';

        if ($this->user()->isAdmin()) {
            $table = $this->getAdminStatistic();
        } elseif ($this->user()->isAgent()) {
            $table = $this->getAgentStatistic();
        }

        return \response()->render($view, ['table' => $table]);
    }

    /**
     * @return Collection
     */
    protected function getAdminStatistic() {
        $users = app('roleRepository')->scopeQuery(function (Role $query) {
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
     * @return array
     */
    protected function getAgentStatistic() {
        $users = $this->user()->children;

        $offersByUser = $users->map(function (User $user) {
            return app('offerRepository')
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
