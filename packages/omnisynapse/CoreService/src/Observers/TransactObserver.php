<?php

namespace OmniSynapse\CoreService\Observers;

use App\Models\NauModels\Transact;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Exception\RequestException;

class TransactObserver extends AbstractJobObserver
{
    /**
     * @param Transact $transact
     *
     * @return bool
     * @throws RequestException
     */
    public function creating(Transact $transact)
    {
        if (!$transact->isTypeP2p()) {
            return true;
        }

        return $this->queue($this->getCoreService()->sendNau($transact));
    }
}
