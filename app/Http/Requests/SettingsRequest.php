<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class SettingsRequest
 * @package App\Http\Requests
 *
 * @property string min_balance_for_change_invite
 * @property string min_level_for_change_invite
 */
class SettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'min_balance_for_change_invite' => 'required|integer|min:0',
            'min_level_for_change_invite'   => 'required|integer|min:0',
        ];
    }
}
