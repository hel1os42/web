<?php

namespace OmniSynapse\CoreService\Observers;

use App\Models\NauModels\Redemption;
use Illuminate\Events\Dispatcher;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Response\OfferForRedemption;

class RedemptionObserver extends AbstractJobObserver
{
    private $eventsDispatcher;

    /**
     * RedemptionObserver constructor.
     *
     * @param Dispatcher  $events Injected
     * @param CoreService $coreService Injected
     */
    public function __construct(Dispatcher $events, CoreService $coreService)
    {
        parent::__construct($coreService);
        $this->eventsDispatcher = $events;
    }

    /**
     * @param Redemption $redemption
     *
     * @return bool
     */
    public function creating(Redemption $redemption)
    {
        $this->eventsDispatcher->listen(OfferForRedemption::class,
            function (OfferForRedemption $response) use ($redemption) {
                $redemption->id = $response->getId();
            });

        return $this->execute($this->getCoreService()->offerRedemption($redemption));
    }
}
