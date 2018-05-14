<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;

class HomeController extends Controller
{
    /**
     * @param Authenticatable $authUser
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Authenticatable $authUser)
    {
        if ($authUser instanceof \App\Models\Operator) {
            return \response()->render('operator', []);
        }

        return \response()->redirectTo(route('statistics'));
    }
}
