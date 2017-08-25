<?php

namespace App\Http\Validators;

use App\Models\NauModels\Account;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class ExtraValidations extends Validator
{
    public function validateItself($attribute, $value, $parameters)
    {
        return Account::where('owner_id', Auth::id())
                ->firstOrFail()
                ->getAddress() === $value;
    }
}
