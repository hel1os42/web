<?php

namespace App\Http\Requests\Service;

use App\Services\InvestorAreaService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

/**
 * Class ExchangeNau
 * NS: App\Http\Requests\Service
 *
 * @property string address
 * @property string direction
 * @property double amount
 */
class ExchangeNau extends FormRequest
{
    public function authorize(InvestorAreaService $service): bool
    {
        return $service->checkRequestSign($this);
    }

    public function rules(): array
    {
        $now = Carbon::now();
        $min = $now->copy()->subMinutes(2);

        return [
            'address'   => 'required|exists:pgsql_nau.account,addr',
            'direction' => 'required|in:in,out',
            'amount'    => 'required|float',
            'timestamp' => sprintf('required|integer|min:%d|max:%d', $min, $now->timestamp),
            'signature' => sprintf('required|string')
        ];
    }
}
