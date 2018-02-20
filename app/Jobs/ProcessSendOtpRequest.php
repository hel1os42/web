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
    public $tries = 5;

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
     * Execute the job.
     */
    public function handle()
    {
        usleep(exp($this->attempts()) / 27 * 1000000);
        $gate = app($this->gateClass);

        list($method, $path, $data, $headers, $auth) = $this->data;

        $result  = $gate->request($method, $path, $data, $headers, $auth);
        $success = $gate->validateResponseString($result);



        if (!$success) {
            if ($this->attempts() > 1) {
                $this->delete();
            }
            $gate->otpError('Gate result not OK.');
        }

        $this->delete();
    }
}
