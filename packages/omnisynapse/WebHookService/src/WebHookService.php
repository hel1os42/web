<?php

namespace OmniSynapse\WebHookService;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use OmniSynapse\WebHookService\Contracts\WebHookService as WebHookServiceContract;
use OmniSynapse\WebHookService\Repositories\Contracts\WebHookEventRepository;
use GuzzleHttp\Middleware as BasicMiddleware;
use OmniSynapse\WebHookService\Repositories\Contracts\WebHookRepository;
use Psr\Log\LoggerInterface;

class WebHookService implements WebHookServiceContract
{
    /**
     * @var WebHookEventRepository
     */
    private $webHookEventRepo;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var WebHookEventRepository
     */
    private $repo;

    public function send(string $url, array $payload)
    {
        $data         = compact('url', 'payload');
        $webHookEvent = $this->getRepo()->create($data);

        $responseBody = '';
        $statusCode   = 0;

        try {
            $response     = $this->getClient()->post($url, ['json' => $payload]);
            $responseBody = $response->getBody()->getContents();
            $statusCode   = $response->getStatusCode();
        } catch (\Throwable $exception) {
            logger()->warning($exception->getMessage(), $data);
        } finally {
            $updateData = [
                'response'    => str_limit($responseBody, 2000),
                'status_code' => $statusCode,
            ];

            $this->getRepo()->update($updateData, $webHookEvent->getKey());
        }
    }

    /**
     * @param Client $client
     * @return WebHookService
     */
    public function setClient(Client $client): WebHookService
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @param WebHookEventRepository $repo
     * @return WebHookService
     */
    public function setRepo(WebHookEventRepository $repo): WebHookService
    {
        $this->client = $repo;

        return $this;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        if (is_null($this->client)) {
            $this->client = $this->initClient();
        }

        return $this->client;
    }

    /**
     * @return WebHookRepository
     */
    public function getRepo(): WebHookEventRepository
    {
        if (is_null($this->repo)) {
            $this->repo = $this->initRepo();
        }

        return $this->repo;
    }

    /**
     * @return Client
     */
    private function initClient(): Client
    {
        $handlerStack = HandlerStack::create();

        $handlerStack->push(Middleware::logToFile(class_basename($this)), 'log_to_file');
        $handlerStack->push(BasicMiddleware::retry(Middleware::createRetryHandler()), 'retry');

        $config = [
            'handler'     => $handlerStack,
            'verify'      => false,
            'http_errors' => false,
        ];

        return new Client($config);
    }

    /**
     * @return WebHookEventRepository
     */
    private function initRepo(): WebHookEventRepository
    {
        return app()->make(WebHookEventRepository::class);
    }
}