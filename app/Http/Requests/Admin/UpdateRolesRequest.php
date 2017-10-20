<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRolesRequest
 * @package App\Http\Requests
 *
 * @property array role_ids
 *
 */
class UpdateRolesRequest extends FormRequest
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
            'role_ids'   => 'required|array',
            'role_ids.*' => 'string|exists:roles,id',
        ];
    }
} 
