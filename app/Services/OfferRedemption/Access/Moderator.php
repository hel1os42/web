<?php

namespace App\Services\OfferRedemption\Access;

interface Moderator
{

    /**
     * @return int
     */
    public function getAccessCode(): int;

    /**
     * @return array
     */
    public function getRestrictions(): array;
}