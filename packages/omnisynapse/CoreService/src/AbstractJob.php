<?php

namespace OmniSynapse\CoreService;

use App\Http\Exceptions\ServiceUnavailableException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OmniSynapse\CoreService\Exception\RequestException;

abstract class AbstractJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var CoreService */
    protected $coreService;

    /**
     * AbstractJob constructor.
     *
     * @param CoreService $coreService
     */
    public function __construct(CoreService $coreService)
    {
        $this->coreService = $coreService;
    }

    /**
     * @return Client
     */
    final protected function getClient()
    {
        return $this->coreService->getClient();
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['coreService'];
    }

    /**
     * Execute the job.
     *
     * @return void
     *
     * @throws RequestException
     * @throws ServiceUnavailableException
     */
    public function handle()
    {
        /** @var Response $response */
        $method   = $this->getHttpMethod();
        $uri      = $this->getHttpPath();
        $jsonBody = null !== $this->getRequestObject()
            ? $this->getRequestObject()->jsonSerialize()
            : null;

        logger()->debug('Sending request', [
            'method' => $method,
            'uri'    => $uri,
            'json'   => $jsonBody
        ]);

        try {
            $response        = $this->getClient()->request($method, $uri, ['json' => $jsonBody]);
            $responseContent = $response->getBody()->getContents();
        } catch (GuzzleException $exception) {
            throw new ServiceUnavailableException(null, $exception);
        } catch(\InvalidArgumentException $exception) {
            throw new ServiceUnavailableException(null, $exception);
        } catch(\LogicException $exception) {
            throw new ServiceUnavailableException(null, $exception);
        }

        if (floor($response->getStatusCode() * 0.01) > 2) {
            throw new RequestException($this, $response, $responseContent);
        }

        logger()->debug('Request and Response', [
            'request'  => $this->getRequestObject()->jsonSerialize(),
            'response' => $responseContent,
        ]);

        $responseObject = $this->getResponseObject();

        $decodedContent = \json_decode($responseContent);
        if (null === $decodedContent) {
            event($responseObject);

            return;
        }

        $responseObject = $this->mapDecodedContent($decodedContent, $responseObject, $response, $responseContent);

        event($responseObject);
    }

    /** @return string */
    abstract public function getHttpMethod(): string;

    /** @return string */
    abstract public function getHttpPath(): string;

    /** @return null|\JsonSerializable */
    abstract public function getRequestObject(): ?\JsonSerializable;

    /** @return object */
    abstract public function getResponseObject();

    /**
     * @param \Exception $exception
     *
     * @return FailedJob
     */
    abstract protected function getFailedResponseObject(\Exception $exception): FailedJob;

    /**
     * @param \Exception $exception
     *
     * @return void
     */
    public function failed(\Exception $exception)
    {
        $failedResponse = $this->getFailedResponseObject($exception);
        event($failedResponse);
    }

    /**
     * @param $decodedContent
     * @param $responseObject
     * @param $response
     * @param $responseContent
     *
     * @return object
     *
     * @throws RequestException
     */
    private function mapDecodedContent($decodedContent, $responseObject, $response, $responseContent)
    {
        $jsonMapper                                = new \JsonMapper();
        $jsonMapper->bExceptionOnMissingData       = true;
        $jsonMapper->bExceptionOnUndefinedProperty = true;
        $jsonMapper->bStrictObjectTypeChecking     = true;

        try {
            $result = $jsonMapper->map($decodedContent, $responseObject);
        } catch (\InvalidArgumentException $e) {
            throw new RequestException($this, $response, $responseContent, $e);
        } catch (\JsonMapper_Exception $e) {
            throw new RequestException($this, $response, $responseContent, $e);
        }

        return $result;
    }
}
