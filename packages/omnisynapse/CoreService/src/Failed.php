<?php

namespace OmniSynapse\CoreService;

/**
 * Class FailedJob
 * @package OmniSynapse\CoreService;
 */
class FailedJob
{
    /** @var \Exception */
    private $exception;

    /**
     * @param \Exception $exception
     */
    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * @return \Exception
     */
    public function getException(): \Exception
    {
        return $this->exception;
    }
}
