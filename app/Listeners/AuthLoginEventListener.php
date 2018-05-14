<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;

class AuthLoginEventListener
{

    /**
     * Handle the event.
     *
     * @param  Login $event
     * @return void
     */
    public function handle(Login $event)
    {
        $event->user->setLastLoggedInAt(Carbon::now())
            ->save();
    }
}
