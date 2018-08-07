<?php

namespace OmniSynapse\WebHookService\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OmniSynapse\WebHookService\Models\WebHook;

class WebHookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'url'      => [
                'string',
                'required',
                'url',
            ],
            'events'   => [
                'array',
                'required',
            ],
            'events.*' => [
                Rule::in(WebHook::getSupportedEventNames()),
            ]
        ];
    }
}
