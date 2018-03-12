<?php

namespace App\Http\Requests\Auth;

use App\Helpers\FormRequest as FormRequestHelper;
use App\Models\User;
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
 * @property string name
 * @property float  latitude
 * @property float  longitude
 * @property array  role_ids
 * @property array  parent_ids
 * @property array  child_ids
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
            'phone'            => 'required_without:email|nullable|regex:/\+[0-9]{10,15}/|unique:users,phone',
            'email'            => 'required_without:phone|nullable|email|max:255|unique:users,email',
            'password'         => 'required_with:email|nullable|min:6|max:255',
            'password_confirm' => 'required_with:email|nullable|same:password'
        ];

        if ($this->getRegistrator() === null) {
            $rules['code']        = 'required_with:phone|nullable|digits:4|otp';
            $rules['referrer_id'] = 'required|string|exists:users,id';
        }

        return $rules;
    }

    /**
     * @return User|null
     */
    public function getRegistrator(): ?User
    {
        return auth()->user();
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public static function preFilledFormRequest()
    {
        return FormRequestHelper::preFilledFormRequest(self::class);
    }
}
