<?php

namespace OmniSynapse\CoreService\Observers;

use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\CoreService;

/**
 * Class AbstractJobObserver
 * NS: OmniSynapse\CoreService\Observers
 */
class AbstractJobObserver
{
    private $coreService;

    /**
     * AbstractJobObserver constructor.
     *
     * @param CoreService $coreService Injected
     */
    public function __construct(CoreService $coreService)
    {
        $this->coreService = $coreService;
    }

    /**
     * @return CoreService
     */
    protected function getCoreService(): CoreService
    {
        return $this->coreService;
    }

    /**
     * @param AbstractJob $job
     *
     * @return bool
     */
    protected function queue(AbstractJob $job): bool
    {
        try {
            dispatch($job);
            $success = true;
        } catch (\Throwable $exception) {
            $success = false;
        }

        return $success;
    }

    /**
     * @param AbstractJob $job
     *
     * @return bool
     */
    protected function execute(AbstractJob $job): bool
    {
        try {
            $job->handle();
            $success = true;
        } catch (\Throwable $exception) {
            $success = false;
        }

        return $success;
    }
}
