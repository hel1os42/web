<?php

namespace App\Services\Implementation;

use Illuminate\Http\Request;

/**
 * Class InvestorAreaService
 * NS: App\Services\Implementation
 */
class InvestorAreaService implements \App\Services\InvestorAreaService
{

    public function checkRequestSign(Request $request): bool
    {
        $data = $request->all();
        ksort($data);
        $key = config('key.secret.investor');

        $stringData = sprintf('%s:%s:%s:%s',
            strtoupper($request->method()), urlencode($request->path()), $this->extractElements($data), $key);

        return hash_hmac("sha1", $stringData, $key) === $request->get('signature');
    }

    private function extractElements(array $data): string
    {
        array_walk($data, function (&$value, $key) {
            $value = sprintf('%s=%s', urlencode($key), urlencode($value));
        });

        return implode('&', $data);
    }
}
