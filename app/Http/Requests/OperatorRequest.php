<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        return [
            'place_uuid' => 'required',
            'login'      => $this->getLoginRule(),
            'password'   => 'required',
            'confirm'    => 'required|same:password',
        ];
    }

    private function getLoginRule()
    {
        $rules = [
            Rule::unique("operators", "login")
                ->where("place_uuid", request()->get("place_uuid")),
            'required',
            'min:3',
        ];

        return implode('|', $rules);
    }
}
