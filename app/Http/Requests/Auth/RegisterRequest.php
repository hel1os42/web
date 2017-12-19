<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RegisterRequest
 * @package App\Http\Requests\Auth
 *
 * @property int    code
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
        return !auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'            => 'required_without:phone|nullable|email|max:255|unique:users,email',
            'password'         => 'required_with:email|nullable|min:6|max:255',
            'password_confirm' => 'required_with:email|nullable|same:password',
            'phone'            => 'required_without:email|nullable|regex:/\+[0-9]{10,15}/|unique:users,phone',
            'code'             => 'required_with:phone|nullable|digits:6|otp',
            'referrer_id'      => 'required|string|exists:users,id'
        ];
    }
}
