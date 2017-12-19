<?php

namespace OmniSynapse\CoreService\Observers;

use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Exception\RequestException;

/**
 * Class AbstractJobObserver
 * NS: OmniSynapse\CoreService\Observers
 */
abstract class AbstractJobObserver
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
        $success = false;

        try {
            dispatch($job);
            $success = true;
        } catch (\Throwable $exception) {
            $this->handleException($exception);
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
        $success = false;

        try {
            $job->handle();
            $success = true;
        } catch (\Throwable $exception) {
            $this->handleException($exception);
        }

        return $success;
    }

    /**
     * @param \Throwable $exception
     *
     * @return void
     * @throws RequestException
     */
    private function handleException(\Throwable $exception): void
    {
        logger()->debug($exception->getTraceAsString());

        logger()->error($exception->getMessage());
        logger()->debug(get_class($exception));

        if ($exception instanceof RequestException) {
            logger()->debug($exception->getRawResponse());
            throw $exception; // re-throw RequestException
        }
    }
}
