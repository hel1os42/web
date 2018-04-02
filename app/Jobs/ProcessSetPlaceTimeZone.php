<?php

namespace App\Jobs;

use App\Exceptions\Exception;
use App\Models\Place;
use App\Services\TimezoneDbService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessSetPlaceTimeZone implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * @var string
     */
    private $timezoneServiceClass;
    /**
     * @var Place
     */
    private $place;

    /**
     * ProcessSetPlaceTimeZone constructor.
     *
     * @param       $timezoneServiceClass
     * @param       $place
     */
    public function __construct($timezoneServiceClass, $place)
    {
        $this->timezoneServiceClass = $timezoneServiceClass;
        $this->place                = $place;
    }

    public function handle()
    {
        /**
         * @var TimezoneDbService
         */
        $timezoneService = app($this->timezoneServiceClass);

        try {
            $timezone = $timezoneService->getTimezoneByLocation($this->place->latitude, $this->place->longitude);
        } catch (Exception $exception) {
            logger(sprintf('Get timezone by location error. Timezone for place %s will be set as UTC. Error message: %s',
                $this->place->id,
                $exception->getMessage()));
            $timezone = new \DateTimeZone('UTC');
        }
        Place::query()->find($this->place->id)->update(['timezone' => $timezone->getName()]);
    }
}
