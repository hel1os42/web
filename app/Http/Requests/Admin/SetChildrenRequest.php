<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SetChildrenRequest
 * @package App\Http\Requests
 *
 * @property array user_ids
 *
 */
class SetChildrenRequest extends FormRequest
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
            'user_ids'   => 'array',
            'user_ids.*' => sprintf(
        'string|regex:%s|exists:users,id',
        \App\Helpers\Constants::UUID_REGEX
    ),
        ];
    }
} 
