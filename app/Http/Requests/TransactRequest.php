<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TransactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sender'      => 'required|exists:pgsql_nau.users,id',
            'destination' => 'required|different:sender|exists:pgsql_nau.users,id|different:sender',
            'amount'      => "required|numeric|min:0.0001",
        ];
    }
}
