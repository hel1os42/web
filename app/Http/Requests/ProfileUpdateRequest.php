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
            'name'      => 'string|min:2',
            'email'     => 'required_without:phone|email|max:255|unique:users,email',
            'phone'     => 'required_without:email|regex:/\+[0-9]{10,15}/|unique:users,phone',
            'latitude'  => 'numeric',
            'longitude' => 'numeric',
        ];
    }
} 
