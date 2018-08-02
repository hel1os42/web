<?php

namespace App\Services\Auth\JWTAuth;

use App\Jobs\InvalidateJWTToken;
use Tymon\JWTAuth\Payload;
use Tymon\JWTAuth\Utils;

/**
 * Class Blacklist
 * @package App\Services\Auth\JWTAuth
 */
class Blacklist extends \Tymon\JWTAuth\Blacklist
{
    public const DELAY_IN_SECONDS = 30;

    /**
     * Add the token (jti claim) to the blacklist.
     *
     * @param  \Tymon\JWTAuth\Payload $payload
     * @return bool
     */
    public function add(Payload $payload)
    {
        $exp        = Utils::timestamp($payload['exp']);
        $refreshExp = Utils::timestamp($payload['iat'])->addMinutes($this->refreshTTL);

        // there is no need to add the token to the blacklist
        // if the token has already expired AND the refresh_ttl
        // has gone by
        if ($exp->isPast() && $refreshExp->isPast()) {
            return false;
        }

        // Set the cache entry's lifetime to be equal to the amount
        // of refreshable time it has remaining (which is the larger
        // of `exp` and `iat+refresh_ttl`), rounded up a minute
        $cacheLifetime = $exp->max($refreshExp)->addMinute()->diffInMinutes();

        $job = (new InvalidateJWTToken($payload['jti'], $cacheLifetime))
            ->delay(self::DELAY_IN_SECONDS);

        dispatch($job);

        return true;
    }
}
