<?php

namespace App\Models\Traits;

trait HasNau
{
    /** @var int $multiplier */
    protected $multiplier;

    /**
     * HasNau constructor.
     */
    public function __construct()
    {
        /** @var int multiplier */
        $this->multiplier = $this->getMultiplier();
    }

    /**
     * @return int
     */
    private function getMultiplierNau(): integer
    {
        return config('nau.multiplier');
    }

    /**
     * @param int $value
     * @return float
     */
    protected function convertIntToFloat(integer $value) : float
    {
        return round($value * pow(0.1, $this->multiplier), $this->multiplier);
    }
}


