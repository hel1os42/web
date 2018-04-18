<?php

namespace App\Jobs;

use App\Exceptions\Exception;
use App\Services\Auth\Otp\BaseOtpAuth;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessSendOtpRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * @var BaseOtpAuth
     */
    private $gateClass;
    /**
     * @var array
     */
    private $data;

    /**
     * Create a new job instance.
     *
     * @param       $gateClass
     * @param array $data
     */
    public function __construct($gateClass, array $data)
    {
        $this->gateClass = $gateClass;
        $this->data      = $data;
    }

    /**
     *
     */
    public function handle()
    {
        /** @var BaseOtpAuth $gate */
        $gate = app($this->gateClass);

        list($method, $path, $data, $headers, $auth) = $this->data;

        try {
            $gate->request($method, $path, $data, $headers, $auth);
        } catch (Exception $exception) {
            logger()->error('Failed to send otp Request:',
                ['message' => $exception->getMessage(), 'code' => $exception->getCode()]);
        }

    }
}
