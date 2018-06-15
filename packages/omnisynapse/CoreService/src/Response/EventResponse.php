<?php

namespace OmniSynapse\CoreService\Response;

use JsonSerializable;

/**
 * Class Event
 * @package OmniSynapse\CoreService\Response
 */
class EventResponse implements JsonSerializable
{
    /**
     * @var bool
     */
    public $success;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'success' => $this->success,
        ];
    }
}
