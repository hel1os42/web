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
            $coreService = $coreService->sendNau($transact);
        }

        if ($transact->isTypeIncoming()) {
            $coreService = $coreService->transactionNotification($transact);
        }

        $coreService->handle();
    }
}
