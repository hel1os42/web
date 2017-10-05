<?php

namespace App\Helpers;

/**
 * Class FormRequest
 * NS: App\Helpers
 */
class FormRequest
{
    public static function preFilledFormRequest(string $formRequestClassName, array $defaultValues = []): array
    {
        $formRequest = new $formRequestClassName;
        if (!$formRequest instanceof \Illuminate\Foundation\Http\FormRequest
            || !method_exists($formRequest, 'rules')) {
            throw new \InvalidArgumentException($formRequestClassName);
        }

        $collection = collect($formRequest->rules())->mapWithKeys(function () {
            return [func_get_arg(1) => null];
        })->merge($defaultValues);

        return $collection->toArray();
    }
}
