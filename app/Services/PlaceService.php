<?php

namespace App\Services;

use App\Models\Place;

/**
 * Interface PlaceService
 * NS: App\Services
 */
interface PlaceService
{
    /**
     * @param Place $place
     * @param bool  $setUserApprovedFlag
     *
     * @return mixed
     */
    public function disapprove(Place $place, bool $setUserApprovedFlag = false);
}
