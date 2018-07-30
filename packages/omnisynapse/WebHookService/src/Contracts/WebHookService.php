<?php

namespace OmniSynapse\WebHookService\Contracts;

interface WebHookService
{
    public function send(string $url, array $payload);
}