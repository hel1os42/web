<?php

namespace OmniSynapse\CoreService;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OmniSynapse\CoreService\Exception\RequestException;
use OmniSynapse\CoreService\Response\BaseResponse;

/**
 * Class AbstractJob
 * @package OmniSynapse\CoreService
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class AbstractJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const GET_MODEL_ATTEMPTS = 3;

    public const URL_EVENTS = '/events';

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
     */
    public function handle()
    {
        /** @var Response $response */
        $method = $this->getHttpMethod();
        $uri    = $this->getHttpPath();

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
        } catch (\Throwable $exception) {
            $this->failed($exception);

            return;
        }

        if (floor($response->getStatusCode() * 0.01) > 2) {
            $this->failed(new RequestException($this, $response, $responseContent));

            return;
        }

        $this->logRequestResponse($responseContent);

        $responseObject = $this->getResponseObject();
        $decodedContent = \json_decode($responseContent);

        if ('' === $responseContent && $responseObject::hasEmptyBody()) {
            $this->eventsDispatch($responseObject);
            return;
        }

        if (null === $decodedContent) {
            return;
        }

        try {
            $responseObject = $this->mapDecodedContent($decodedContent, $responseObject, $response, $responseContent);
        } catch (RequestException $e) {
            $this->failed(new RequestException($this, $response, $responseContent));

            return;
        }

        $this->eventsDispatch($responseObject);
    }

    /** @return string */
    abstract public function getHttpMethod(): string;

    /** @return string */
    abstract public function getHttpPath(): string;

    /** @return null|\JsonSerializable */
    abstract public function getRequestObject(): ?\JsonSerializable;

    /** @return BaseResponse */
    abstract public function getResponseObject(): BaseResponse;

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
        logger("Failed Job.", ['exception' => $exception]);

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
        $jsonMapper->bExceptionOnUndefinedProperty = false;
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

    /**
     * @param $responseObject
     */
    private function eventsDispatch($responseObject)
    {
        event($responseObject);
        try {
            $this->fireModelEvents($responseObject);
        } catch (\Exception $exception) {
            logger()->warning(
                'Failed model event firing at ' . get_class($this),
                [
                    'response' => $responseObject,
                    'error'    => $exception->getMessage()
                ]
            );
        }
    }

    /**
     * @param $responseObject
     * @SuppressWarnings("unused")
     */
    protected function fireModelEvents($responseObject): void
    {
    }

    /**
     * @param null|string $responseContent
     */
    protected function logRequestResponse(?string $responseContent): void
    {
        logger()->debug('Request and Response', [
            'request'  => (null !== $this->getRequestObject()) ? $this->getRequestObject()->jsonSerialize() : null,
            'response' => $responseContent,
        ]);
    }

    /**
     * @param     $modelId
     * @param int $attempt
     *
     * @return Model|null
     * @throws ModelNotFoundException
     */
    protected function getModel($modelId, int $attempt = 1): ?Model
    {
        $model = null;
        try {
            $model = $this->getConcreteModel($modelId);
        } catch (ModelNotFoundException $exception) {
            if ($attempt < self::GET_MODEL_ATTEMPTS) {
                logger()->info('attempt', [$attempt]);
                usleep(200 * $attempt);
                return $this->getModel($modelId, ++$attempt);
            }
            throw $exception;
        }
        return $model;
    }

    /**
     * @param $modelId
     * @throws ModelNotFoundException
     * @SuppressWarnings("unused")
     */
    protected function getConcreteModel($modelId)
    {
    }
}
