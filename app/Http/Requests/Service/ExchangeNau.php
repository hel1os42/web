<?php

namespace App\Http\Requests\Service;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ExchangeNau
 * NS: App\Http\Requests\Service
 *
 * @property string address
 * @property string ethAddress
 * @property string direction
 * @property double amount
 */
class ExchangeNau extends FormRequest
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
            'ethAddress' => 'required|string|size:42|regex:/^0x[a-fA-F0-9]{40}$/',
            'address'    => 'required|exists:pgsql_nau.account,addr',
            'direction'  => 'required|in:in,out',
            'amount'     => 'required|numeric',
            'timestamp'  => sprintf('required|integer|min:%d|max:%d', $min->timestamp, $now->timestamp),
            'signature'  => sprintf('required|string')
        ];
    }
}
