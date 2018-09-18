<?php

namespace App\Http\Controllers;

use Rap2hpoutre\LaravelLogViewer\LogViewerController as BaseLogViewerController;

/**
 * Class LogViewerController
 * @package App\Http\Controllers
 */
class LogViewerController extends BaseLogViewerController
{
    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function index()
    {
        if (false === auth()->check() || false === auth()->user()->isAdmin()) {
            abort(404);
        }

        return parent::index();
    }
}
