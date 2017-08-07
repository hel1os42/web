<?php

namespace OmniSynapse\CoreService\Response;

/**
 * Class Error
 * @package OmniSynapse\CoreService\Response
 */
class Error implements \JsonSerializable
{
    /** @var string */
    public $error;

    /** @var string */
    public $message;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'error'   => $this->getError(),
            'message' => $this->getMessage(),
        ];
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
