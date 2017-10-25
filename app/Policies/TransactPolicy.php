<?php

namespace App\Policies;

class TransactPolicy extends Policy
{
    /**
     * @return bool
     */
    public function createTransaction()
    {
        return $this->auth->user()->hasAnyRole();
    }

    /**
     * @return bool
     */
    public function completeTransaction()
    {
        return $this->auth->user()->hasAnyRole();
    }

    /**
     * @return bool
     */
    public function listTransactions()
    {
        return $this->auth->user()->hasAnyRole();
    }
}
