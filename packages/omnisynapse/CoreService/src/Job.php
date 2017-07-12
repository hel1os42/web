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
}