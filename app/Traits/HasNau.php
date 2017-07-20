<?php

namespace App\Models\Traits;

trait HasNau
{
    /** @var int $multiplierNau */
    protected $multiplierNau;

    /**
     * HasNau constructor.
     */
    public function __construct()
    {
        /** @var int multiplierNau */
        $this->multiplierNau = $this->getMultiplierNau();
    }

    /**
     * @return int
     */
    private function getMultiplierNau(): integer
    {
        return config('multiplier_nau_config.multiplier_nau');
    }

    /**
     * @param int $value
     * @return float
     */
    protected function convertIntToFloat(integer $value) : float
    {
        return round($value * pow(0.1, $this->multiplierNau), $this->multiplierNau);
    }
}


