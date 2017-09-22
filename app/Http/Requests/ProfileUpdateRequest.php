<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ProfileUpdateRequest
 * @package App\Http\Requests
 *
 * @property string code
 *
 */
class ProfileUpdateRequest extends FormRequest
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
            'name'             => 'string|min:2',
            'email'            => sprintf('required_without:phone|email|max:255|unique:users,email,%s',
                auth()->id()),
            'phone'            => sprintf('required_without:email|regex:/\+[0-9]{10,15}/|unique:users,phone,%s',
                auth()->id()),
            'password'         => 'min:6|max:255',
            'password_confirm' => 'same:password',
            'latitude'         => 'numeric|between:-90,90',
            'longitude'        => 'numeric|between:-180,180',
        ];
    }
} 
