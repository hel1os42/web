<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class OperatorRequest
 * @package App\Http\Requests
 *
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
        return [
            'place_uuid' => 'required',
            'login'      => sprintf('required|min:3|unique:operators,login,%s,place_uuid', request()->place_uuid),
            'password'   => 'required',
            'confirm'    => 'required|same:password',
        ];
    }
}
