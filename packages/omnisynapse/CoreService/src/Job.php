<?php

namespace OmniSynapse\CoreService;

use GuzzleHttp\Psr7\Response;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

abstract class Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var object */
    protected $requestObject = null;

    /** @var object */
    protected $responseContent = null;

    /**
     * Execute the job.
     *
     * @return void
     * @throws \JsonMapper_Exception
     */
    public function handle()
    {
        /** @var Response $response */
        $response = (new Client())->client->request($this->getHttpMethod(), $this->getHttpPath(),
            [
                'json' => null !== $this->requestObject
                    ? $this->getRequestObject()->jsonSerialize()
                    : null
            ]
        );

        if (floor($response->getStatusCode() * 0.01) > 2) {
            $this->handleError($response);
            return;
        }

        $responseClassName = $this->getResponseClass();
        $this->responseContent = \GuzzleHttp\json_decode($response->getBody()->getContents());

        try {
            (new \JsonMapper)->map($this->responseContent, $responseObject = new $responseClassName);
        } catch (\JsonMapper_Exception $e) {
            $this->handleError($response);
            return;
        }

        event($responseObject);
    }

    /**
     * @param string $fileName
     * @return void
     */
    protected function changeLoggerPath(string $fileName)
    {
        $maxFiles = config('app.log_max_files');
        $monolog = logger()->getMonolog();

        while (count($monolog->getHandlers()) > 0) {
            $monolog->popHandler();
        }

        logger()->useDailyFiles(
            sprintf('%s/logs/%s', storage_path(), $fileName),
            is_null($maxFiles)
                ? 5
                : $maxFiles,
            config('app.log_level', 'debug')
        );
    }

    /** @return string */
    abstract protected function getHttpMethod() : string;

    /** @reutnr string */
    abstract protected function getHttpPath() : string;

    /** @return \JsonSerializable */
    abstract protected function getRequestObject() : \JsonSerializable;

    /** @return string */
    abstract protected function getResponseClass() : string;

    abstract protected function handleError(Response $response);
}