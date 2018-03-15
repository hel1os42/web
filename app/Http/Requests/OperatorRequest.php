<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Class OperatorRequest
 * @package App\Http\Requests
 *
 * @property string place_uuid
 * @property string login
 * @property string password
 * @property string confirm
 *
 */
class OperatorRequest extends FormRequest
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
        $loginRule = $this->method() === 'POST' ?
            Rule::unique('operators', 'login')
                ->where('place_uuid', $this->request->get('place_uuid')):
            Rule::unique('operators', 'login')
                ->where('place_uuid', $this->request->get('place_uuid'))
                ->ignore($this->request->get('id'), 'id');

        $patchMeth = $this->method() === 'PATCH' ? 'nullable' : 'required';

        return [
            'is_active'  => [$patchMeth, 'boolean'],
            'place_uuid' => [$patchMeth, sprintf('regex:%s',\App\Helpers\Constants::UUID_REGEX)],
            'login'      => [$patchMeth, 'min:3', 'max:255', $loginRule],
            'password'   => [$patchMeth],
            'confirm'    => [$patchMeth,'same:password'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function (Validator $validator) {
            if (!$this->request->get('place_uuid') ||
                $this->method() === 'PATCH' && !$this->request->get('id')) {
                $validator->errors()->add('error', trans('validation.operator_requst_update'));
            }
        });
    }
}
