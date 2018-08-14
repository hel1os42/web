<?php

namespace App\Http\Requests;

use App\Helpers\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * Class ProfileUpdateRequest
 * @package App\Http\Requests
 *
 * @property string name
 * @property string email
 * @property string phone
 * @property float  latitude
 * @property float  longitude
 * @property array  role_ids
 * @property array  parent_ids
 * @property array  child_ids
 * @property bool   approve
 *
 */
class UserUpdateRequest extends FormRequest
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
        $rules = [
            'name'                 => 'string|min:2',
            'email'                => sprintf('required_without:phone|nullable|email|max:255|unique:users,email,%s',
                request()->id),
            'phone'                => sprintf('required_without:email|nullable|regex:/\+[0-9]{10,15}/|unique:users,phone,%s',
                request()->id),
            'latitude'             => 'nullable|numeric|between:-90,90',
            'longitude'            => 'nullable|numeric|between:-180,180',
            'role_ids'             => 'array',
            'role_ids.*'           => 'string|exists:roles,id',
            'parent_ids'           => 'array',
            'parent_ids.*'         => sprintf(
                'string|regex:%s|exists:users,id',
                \App\Helpers\Constants::UUID_REGEX
            ),
            'approve'               => 'boolean',
            'invite_code'           => sprintf('nullable|alpha_dash|unique:users,invite_code,%s', request()->id),
            'password'              => 'nullable|string|confirmed|min:6|required_with:password_confirmation',
            'eth_address'           => [
                'nullable',
                'string',
                sprintf('regex:%1$s', Constants::ETH_ADDRESS_REGEX),
                sprintf('unique:users,eth_address,$1%s', request()->id),
            ],
        ];

        if ($this->isMethod(Request::METHOD_PATCH)) {
            $rules['email'] = sprintf('nullable|email|max:255|unique:users,email,%s', request()->id);
            $rules['phone'] = sprintf('nullable|regex:/\+[0-9]{10,15}/|unique:users,phone,%s', request()->id);
        }

        return $rules;
    }
} 
