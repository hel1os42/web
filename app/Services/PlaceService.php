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

    /**
     * @param array $specialities
     *
     * @return array
     */
    public function parseSpecialities(array $specialities): array;

    /**
     * @param string $category
     * @param array  $tags
     *
     * @return array
     */
    public function parseTags(string $category, array $tags): array;
}
