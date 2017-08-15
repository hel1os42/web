<?php

namespace App\Observers;

use App\Models\NauModels\Transact;
use OmniSynapse\CoreService\CoreService;

class TransactObserver
{
    /**
     * @param Transact $transact
     */
    public function creating(Transact $transact)
    {
        if (!$transact->isTypeP2p()) {
            return;
        }

        $coreService = app()->make(CoreService::class);
        dispatch($coreService->sendNau($transact));
    }
}
