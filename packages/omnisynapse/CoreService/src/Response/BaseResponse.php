<?php

namespace OmniSynapse\CoreService\Response;

/**
 * Class BaseResponse
 * @package OmniSynapse\CoreService\Response
 */
class BaseResponse
{
    /**
     * @static bool
     */
    protected static $hasEmptyBody = false;

    /**
     * @static
     * @return bool
     */
    public static function hasEmptyBody(): bool
    {
        return static::$hasEmptyBody;
    }
}
