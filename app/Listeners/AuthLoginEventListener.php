<?php

namespace App\Listeners;

use App\Models\Operator;
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
        if ($event->user instanceof Operator) {
            $event->user->setLastLoggedInAt(Carbon::now())
                ->save();
        }
    }
}
