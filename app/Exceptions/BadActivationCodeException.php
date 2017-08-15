<?php

namespace App\Exceptions;


class BadActivationCodeException extends \Exception
{

    public function __construct()
    {
        parent::__construct();
    }

}