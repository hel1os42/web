<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;
use Symfony\Component\HttpFoundation\Response;

class StatisticsController extends Controller
{
    /**
     * @param StatisticsService $statisticsService
     *
     * @return Response
     */
    public function index(StatisticsService $statisticsService): Response
    {
        $statistics = [];

        if ($this->user()->isAdmin()) {
            $statistics = $statisticsService->getAdminStatistic($this->user());
        } elseif ($this->user()->isAgent()) {
            $statistics = $statisticsService->getAgentStatistic($this->user());
        }

        return \response()->render('statistics', ['data' => $statistics]);
    }
}
