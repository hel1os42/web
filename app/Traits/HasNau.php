<?php

namespace App\Models\Traits;

trait HasNau
{
    /** @var int $multiplier */
    protected $multiplier = null;

    /**
     * @param int $value
     * @return float
     */
    protected function convertIntToFloat(integer $value) : float
    {
        $multiplier = $this->multiplier;

        if (null === $multiplier) {
            $multiplier = $this->multiplier = (int)config('nau.multiplier');
        }

        return round($value * pow(0.1, $multiplier), $multiplier);
    }
}
