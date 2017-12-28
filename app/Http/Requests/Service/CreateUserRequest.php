<?php

namespace App\Http\Requests\Service;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateUserRequest
 * NS: App\Http\Requests\Service
 *
 * @property string email
 * @property string phone
 */
class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $now = Carbon::now();
        $min = $now->copy()->subMinutes(2);

        return [
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'required|regex:/\+[0-9]{10,15}/|unique:users,phone',
            'timestamp' => sprintf('required|integer|min:%d|max:%d', $min->timestamp, $now->timestamp),
            'signature' => sprintf('required|string')
        ];
    }
}
