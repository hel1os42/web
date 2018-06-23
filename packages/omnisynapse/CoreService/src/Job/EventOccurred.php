<?php

namespace OmniSynapse\CoreService\Job;

use App\Events\UserEvent;
use Illuminate\Http\Request;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\FailedJob;
use OmniSynapse\CoreService\Request\EventRequest;
use OmniSynapse\CoreService\Response\BaseResponse;
use OmniSynapse\CoreService\Response\EventResponse;

/**
 * Class EventOccurred
 * @package OmniSynapse\CoreService\Job
 */
class EventOccurred extends AbstractJob
{
    /**
     * @var UserEvent
     */
    public $event;

    /**
     * EventOccurred constructor.
     *
     * @param CoreService $coreService
     * @param UserEvent   $event
     */
    public function __construct(CoreService $coreService, UserEvent $event)
    {
        parent::__construct($coreService);

        $this->event = $event;
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return Request::METHOD_POST;
    }

    /**
     * @return string
     */
    public function getHttpPath(): string
    {
        return self::URL_EVENTS;
    }

    /**
     * @return null|\JsonSerializable
     */
    public function getRequestObject(): ?\JsonSerializable
    {
        return new EventRequest($this->event);
    }

    /**
     * @return BaseResponse
     */
    public function getResponseObject(): BaseResponse
    {
        return new EventResponse();
    }

    /**
     * @param \Exception $exception
     *
     * @return FailedJob
     */
    protected function getFailedResponseObject(\Exception $exception): FailedJob
    {
        return new FailedJob\EventOccurred($exception, $this->event);
    }

    /**
     * Prepare the instance for serialization.
     *
     * @return array
     */
    public function __sleep()
    {
        return ['coreService', 'event'];
    }
}
