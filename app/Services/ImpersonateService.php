<?php

namespace App\Services;

/**
 * Interface ImpersonateService
 * @package App\Services
 */
interface ImpersonateService
{
    public function impersonatedByAdminOrAgent();
}
