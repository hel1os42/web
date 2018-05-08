<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

interface StatisticsService
{
    /**
     * @param User $user
     *
     * @return Collection
     */
    public function getAdminStatistic(User $user): Collection;

    /**
     * @param User $user
     *
     * @return array
     */
    public function getAgentStatistic(User $user): array;
}
