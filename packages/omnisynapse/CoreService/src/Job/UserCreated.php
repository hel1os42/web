<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\User;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Exception\RequestException;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\User as UserRequest;
use OmniSynapse\CoreService\Response\User as UserResponse;

/**
 * Class UserCreated
 * @package OmniSynapse\CoreService\Job
 */
class UserCreated extends Job
{
    /**
     * UserCreated constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        /** @var UserRequest requestObject */
        $this->requestObject = (new UserRequest())
            ->setId($user->getId())
            ->setUsername($user->getName())
            ->setReferrerId($user->getReferrerId());
    }

    /**
     * @return string
     */
    public function getHttpMethod() : string
    {
        return Client::METHOD_PUT;
    }

    /**
     * @return string
     */
    public function getHttpPath() : string
    {
        return '/users';
    }

    /**
     * @return \JsonSerializable
     */
    protected function getRequestObject() : \JsonSerializable
    {
        return $this->requestObject;
    }

    /**
     * @return string
     */
    protected function getResponseClass() : string
    {
        return UserResponse::class;
    }

    /**
     * @param Response $response
     * @throws RequestException
     */
    public function handleError(Response $response)
    {
        $errorMessage = isset($this->responseContent->error)
            ? $this->responseContent->error
            : 'undefined exception reason';
        $requestParams = serialize($this->requestObject->jsonSerialize());
        $logMessage = 'Exception while executing '.self::class.'. Response message: `'.$errorMessage.'`, status: `'.$response->getStatusCode().'.`, Request: '.$requestParams.'.';

        $this->changeLoggerPath('UserCreated');
        logger()->error($logMessage);

        throw new RequestException($logMessage);
    }
}