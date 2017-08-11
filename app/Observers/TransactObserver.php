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
        $coreService = app()->make(CoreService::class);

        if ($transact->isTypeP2p()) {
            dispatch($coreService->sendNau($transact));
        }
    }
}
