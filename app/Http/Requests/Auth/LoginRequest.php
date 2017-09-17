<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LoginRequest
 * @package App\Http\Requests\Auth
 *
 * @property string name
 * @property string email
 * @property string password
 * @property string password_confirm
 * @property string referrer_id
 */
class LoginRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !\Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => 'required_with:password|email|max:255',
            'password' => 'required_with:email|min:6|max:255',
            'phone'    => 'required_with:code|regex:/\+[0-9]{10,15}/',
            'code'     => 'required_with:phone|digits:6'
        ];
    }
} 
