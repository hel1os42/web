<?php

namespace App\Policies;

class TransactPolicy extends Policy
{
    /**
     * @return bool
     */
    public function index()
    {
        return $this->auth->user()->hasAnyRole();
    }

    /**
     * @return bool
     */
    public function create()
    {
        return $this->auth->user()->hasAnyRole();
    }

    /**
     * @return bool
     */
    public function complete()
    {
        return $this->auth->user()->hasAnyRole();
    }
}
