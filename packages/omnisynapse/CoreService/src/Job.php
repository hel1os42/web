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

    /** @var object */
    protected $requestObject = null;

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
        $this->client->request(
            $this->getHttpMethod(),
            $this->getHttpPath(),
            [
                'json' => null !== $this->requestObject
                    ? $this->getRequestObject()->jsonSerialize()
                    : null
            ]
        );
        $mapper = new \JsonMapper();
        $responseClassName = dirname(__FILE__).'/Response/'.$this->getResponseClass();
        $responseObject = $mapper->map($this->client->getResponse(), new $responseClassName);

        event($responseObject); // TODO: or $requestObject? how I can use this event? who can explain me?)
        return $responseObject;
    }

    /** @return string */
    abstract protected function getHttpMethod() : string;

    /** @reutnr string */
    abstract protected function getHttpPath() : string;

    /** @return \JsonSerializable */
    abstract protected function getRequestObject() : \JsonSerializable;

    /** @return string */
    abstract protected function getResponseClass() : string;
}