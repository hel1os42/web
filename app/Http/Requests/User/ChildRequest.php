<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ChildRequest
 * @package App\Http\Requests\User
 *
 * @property array children_ids
 *
 */
class ChildRequest extends FormRequest
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
            'children_ids'            => 'array',
            'children_ids.*'          => sprintf(
                'string|regex:%s|exists:users,id',
                \App\Helpers\Constants::UUID_REGEX
            ),
        ];
    }
}
