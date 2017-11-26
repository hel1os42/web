<?php

namespace App\Policies;

class TransactPolicy extends Policy
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
    public function create()
    {
        return $this->user->hasAnyRole();
    }
}
