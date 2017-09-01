<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class TransactRequest
 * @package App\Http\Requests
 *
 * @property string sender
 * @property string destination
 * @property float amount
 */
class TransactRequest extends FormRequest
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
            'source'      => 'required|ownAddress',
            'destination' => 'required|different:sender|exists:pgsql_nau.account,addr',
            'amount'      => 'required|numeric|min:0.0001',
        ];
    }
}
