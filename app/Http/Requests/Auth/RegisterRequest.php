<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RegisterRequest
 * @package App\Http\Requests\Auth
 *
 * @property int code
 * @property string phone
 * @property string email
 * @property string password
 * @property string password_confirm
 * @property string referrer_id
 */
class RegisterRequest extends FormRequest
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
            'email'            => 'required_with:password,password_confirm|email|max:255|unique:users,email',
            'password'         => 'required_with:email|min:6|max:255',
            'password_confirm' => 'required_with:email|same:password',
            'phone'            => 'required_with:code|regex:/\+[0-9]{10,15}/',
            'code'             => 'required_with:phone|digits:6',
            'referrer_id'      => 'required|string|exists:users,id'
        ];
    }
} 
