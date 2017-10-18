<?php

namespace App\Policies;

class CategoryPolicy extends Policy
{
    /**
     * @return bool
     */
    public function index()
    {
        return $this->hasAnyRole();
    }

    /**
     * @return bool
     */
    public function show()
    {
        return $this->hasAnyRole();
    }
}
