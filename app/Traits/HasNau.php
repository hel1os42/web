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
    protected function convertIntToFloat(int $value): float
    {
        return $this->convertBase((float)$value, 0.1);
    }

    /**
     * @param float $value
     * @return int
     */
    protected function convertFloatToInt(float $value): int
    {
        return (int)$this->convertBase($value, 10.0);
    }

    /**
     * @param float $value
     * @param float $base
     * @return float
     */
    private function convertBase(float $value, float $base): float
    {
        $multiplier = $this->multiplier;

        if (null === $multiplier) {
            $multiplier = $this->multiplier = (int)config('nau.multiplier');
        }

        return round($value * pow($base, $multiplier), $multiplier);
    }
}
