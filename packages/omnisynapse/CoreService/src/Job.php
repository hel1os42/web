<?php

namespace OmniSynapse\CoreService;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

abstract class Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Client */
    private $client;

    /**
     * Job constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Execute the job.
     *
     * @return object
     */
    public function handle() : object
    {
        $requestObject = $this->getRequestObject();
        $this->client->request(
            $this->getHttpMethod(),
            $this->getHttpPath(),
            [
                'json' => null !== $requestObject ? $requestObject->jsonSerialize() : null
            ]
        );
        $mapper = new \JsonMapper();
        $responseObject = $mapper->map($this->client->getResponse(), $this->getResponseClass()); // TODO: must to be an object, instead string.
        event($responseObject); // TODO: or $requestObject? how I can use this event? who can explain me?)
    }

    /** @return string */
    abstract public function getHttpMethod() : string;

    /** @reutnr string */
    abstract protected function getHttpPath() : string;

    /** @return \JsonSerializable */
    abstract protected function getRequestObject() : ?\JsonSerializable;

    /** @return object */
    abstract protected function getResponseClass(); // TODO: typehint for return
}