<?php
/**
 * web
 * ©2017 necromancer
 * Date: 9/21/17
 * Time: 17:05
 */

namespace App\Services\Auth\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface PhoneAuthenticable extends Authenticatable
{
    public function getPhone(): ?string;
}
