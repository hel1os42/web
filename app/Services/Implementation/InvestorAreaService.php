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
        $key = config('nau.key_secret_investor');

        $timestamp = $request->get('timestamp');
        if (!is_numeric($timestamp) || $timestamp > time() || time() - 120 > $timestamp) {
            return false;
        }

        $stringData = sprintf('%s:%s:%s:%s',
            strtoupper($request->method()), urlencode($request->path()), $this->extractElements($data), $key);

        return hash_hmac("sha1", $stringData, $key) === $request->get('signature');
    }

    private function extractElements(array $data): string
    {
        $data = array_except($data, 'signature');

        array_walk($data, function (&$value, $key) {
            $value = sprintf('%s=%s', urlencode($key), urlencode($value));
        });

        return implode('&', $data);
    }
}
