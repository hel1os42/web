<?php

namespace App\Policies;

class CategoryPolicy extends Policy
{
    /**
     * @return bool
     */
    public function index()
    {
        return $this->user->hasAnyRole();
    }

    /**
     * @return bool
     */
    public function show()
    {
        return $this->user->hasAnyRole();
    }
}
