<?php

namespace OmniSynapse\WebHookService\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use OmniSynapse\WebHookService\Contracts\Hookable;
use OmniSynapse\WebHookService\Contracts\WebHookService;

class SendWebHook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Hookable
     */
    private $event;

    /**
     * @var WebHookService
     */
    private $service;

    /**
     * @var string
     */
    private $url;

    /**
     * Create a new job instance.
     *
     * @param WebHookService $service
     * @param string         $url
     * @param Hookable       $event
     */
    public function __construct(WebHookService $service, string $url, Hookable $event)
    {
        $this->service = $service;
        $this->event   = $event;
        $this->url     = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->service->send($this->url, $this->event->getPayload());
    }
}
