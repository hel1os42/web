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

    /**
     * @var Client
     */
    protected $client;

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
    public function handle()
    {
        return $this->client->request($this->getHttpMethod(), $this->getHttpPath(), $this->getArrayParams())->getContent();
    }

    /**
     * @return string
     */
    abstract public function getHttpMethod();

    /**
     * @return string
     */
    abstract protected function getHttpPath();
}