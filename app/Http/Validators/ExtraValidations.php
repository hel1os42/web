<?php

namespace App\Http\Validators;

use App\Models\NauModels\Account;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Auth;

/**
 * Class ExtraValidations
 * @package App\Http\Validators
 */
class ExtraValidations extends Validator
{
    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @SuppressWarnings("unused")
     * @return bool
     */
    public function validateItself($attribute, $value, $parameters): bool
    {
        $account = Account::whereAddress($value ?? null)
            ->whereOwnerId(auth()->user()->id)
            ->first();
        return null !== $account;
    }
}
