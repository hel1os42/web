<?php

namespace App\Console;

use App\Console\Commands\FixZeroReservedOffers;
use App\Console\Commands\SetHasActiveOffersFlag;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        FixZeroReservedOffers::class,
        SetHasActiveOffersFlag::class
    ];

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
