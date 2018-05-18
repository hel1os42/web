<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LoginRequest
 * @package App\Http\Requests\Auth
 *
 * @property string email
 * @property string password
 * @property string phone
 * @property string code
 * @property string alias
 * @property string login
 * @property string pin
 * @property string provider
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
            'email'                 => 'required_without_all:phone,alias,identity_access_token|nullable|email|max:255',
            'password'              => 'required_with:email|nullable|min:6|max:255',
            'phone'                 => 'required_without_all:email,alias,identity_access_token|nullable|regex:/\+[0-9]{10,15}/',
            'code'                  => 'required_with:phone|nullable|digits:4|otp',
            'alias'                 => 'required_without_all:phone,email,identity_access_token|nullable|min:3|max:255',
            'login'                 => 'required_with:alias|nullable|min:3|max:255',
            'pin'                   => 'required_with:alias|nullable|different:alias|different:login|min:3|max:255',
            'identity_provider'     => 'required_with:identity_access_token|string',
            'identity_access_token' => 'required_without_all:phone,email,alias|nullable|string',
        ];
    }

    public function credentials()
    {
        if (null !== $this->alias) {
            return $this->aliasCredentials();
        }

        if (null !== $this->email) {
            return $this->emailCredentials();
        }

        if (null !== $this->phone) {
            return $this->phoneCredentials();
        }

        return $this->getIdentityCredentials();
    }

    private function emailCredentials()
    {
        return [
            'email'    => $this->email,
            'password' => $this->password,
        ];
    }

    private function phoneCredentials()
    {
        return [
            'phone' => $this->phone,
            'code'  => $this->code,
        ];
    }

    private function aliasCredentials()
    {
        return [
            'login'    => $this->login,
            'password' => $this->pin,
            'alias'    => $this->alias,
        ];
    }

    private function getIdentityCredentials()
    {
        return array_only($this->all(), [
            'identity_provider',
            'identity_access_token',
        ]);
    }
}
