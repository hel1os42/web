<?php

namespace App\Policies;

class ActivationCodePolicy extends Policy
{
    /**
     * @return bool
     */
    public function show()
    {
        return $this->isUser();
    }
}
