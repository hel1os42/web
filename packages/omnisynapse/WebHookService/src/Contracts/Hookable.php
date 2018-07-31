<?php

namespace OmniSynapse\WebHookService\Contracts;

interface Hookable
{
    /**
     * @return array
     */
    public function getPayload(): array;
}
