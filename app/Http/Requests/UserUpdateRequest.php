<?php

namespace App\Http\Requests;

use App\Helpers\Constants;
use App\Http\Exceptions\NotFoundException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use App\Repositories\UserRepository;
use App\Models\User;
use App\Models\Role;
use App\Repositories\RoleRepository;

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
    protected $userId;
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
        $this->userId = request()->path() === 'profile' ? auth()->user()->id : request()->id;

        $rules = [
            'name'         => 'string|min:2',
            'email'        => sprintf('required_without:phone|nullable|email|max:255|unique:users,email,%s',
                $this->userId),
            'phone'        => sprintf('required_without:email|nullable|regex:/\+[0-9]{10,15}/|unique:users,phone,%s',
                $this->userId),
            'latitude'     => 'nullable|numeric|between:-90,90',
            'longitude'    => 'nullable|numeric|between:-180,180',
            'role_ids'     => 'array',
            'role_ids.*'   => 'string|exists:roles,id',
            'parent_ids'   => 'array',
            'parent_ids.*' => sprintf(
                'string|regex:%s|exists:users,id',
                \App\Helpers\Constants::UUID_REGEX
            ),
            'approve'               => 'boolean',
            'invite_code'           => sprintf('nullable|alpha_dash|unique:users,invite_code,%s', $this->userId),
            'password'              => 'nullable|string|confirmed|min:6|required_with:password_confirmation',
            'eth_address'           => [
                'nullable',
                'string',
                sprintf('regex:%1$s', Constants::ETH_ADDRESS_REGEX),
                sprintf('unique:users,eth_address,$1%s', $this->userId),
            ],
        ];

        if ($this->isMethod(Request::METHOD_PATCH)) {
            $rules['email'] = sprintf('nullable|email|max:255|unique:users,email,%s', $this->userId);
            $rules['phone'] = sprintf('nullable|regex:/\+[0-9]{10,15}/|unique:users,phone,%s', $this->userId);
        }

        return $rules;
    }

    /**
     * @param  Validator $validator
     *
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function (Validator $validator) {
            $user = $this->checkUser();

            if ($this->hasRole(Role::ROLE_ADVERTISER) && $user->children()->count() > 0) {
                $validator->errors()->add('error', trans('validation.user_children_excess'));
            }

            if ($this->hasRole(Role::ROLE_CHIEF_ADVERTISER) && $user->countHasOffers() > 0) {
                $validator->errors()->add('error', trans('validation.user_offer_excess'));
            }
        });
    }

    /**
     * @param string $roleName
     *
     * @return bool
     */
    private function hasRole(string $roleName): bool
    {
        $role = app(RoleRepository::class)->findByField('name', $roleName)->first();

        return $role instanceof Role ? in_array($role->getId(), $this->get('role_ids', [])) : false;
    }

    /**
     * @param void
     *
     * @throws NotFoundException
     * @return User
     */
    private function checkUser(): User
    {
        $user = app(UserRepository::class)->find($this->userId);

        if ($user instanceof User) {

            return $user;
        }

        throw new NotFoundException();
    }
}
