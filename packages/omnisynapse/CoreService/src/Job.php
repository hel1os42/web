<?php

namespace OmniSynapse\CoreService;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Response;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use OmniSynapse\CoreService\Exception\RequestException;
use OmniSynapse\CoreService\Request\User;

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
     * @throws
     */
    public function handle()
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
        $responseClassName = $this->getResponseClass();
        $content = $this->client->getContent();

        if (isset($content->status) && Response::HTTP_OK !== $content->status) {
            $error = isset($content->error)
                ? $content->error
                : 'Undefined error';
            throw new RequestException($error);
        }
        $responseObject = (new \JsonMapper())->map($content, new $responseClassName);

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