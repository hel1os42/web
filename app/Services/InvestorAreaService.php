<?php

namespace App\Services;

use Illuminate\Http\Request;

/**+
 * Interface InvestorAreaService
 * NS: App\Services
 */
interface InvestorAreaService
{
    function checkRequestSign(Request $request): bool;
}
