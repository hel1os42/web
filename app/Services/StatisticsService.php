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
    public function getStatisticsFor(User $user): Collection;
}
