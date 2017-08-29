<?php

namespace OmniSynapse\CoreService\Failed;

/**
 * Class Failed
 * @package OmniSynapse\CoreService\Failed;
 */
class Failed
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
