<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Tymon\JWTAuth\Providers\Storage\StorageInterface;

class InvalidateJWTToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $cacheLifetime;

    /**
     * Create a new job instance.
     *
     * @param string $id
     * @param int    $cacheLifetime
     */
    public function __construct(string $id, int $cacheLifetime)
    {
        $this->id            = $id;
        $this->cacheLifetime = $cacheLifetime;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var StorageInterface $storage */
        $storage = app()->make(StorageInterface::class);

        $storage->add($this->id, [], $this->cacheLifetime);
    }
}
