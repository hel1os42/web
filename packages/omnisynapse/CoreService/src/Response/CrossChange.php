<?php

namespace OmniSynapse\CoreService\Response;

/**
 * Class CrossChange
 * NS: OmniSynapse\CoreService\Response
 *
 * @method static bool hasEmptyBody()
 */
class CrossChange extends BaseResponse
{
    /** @var Transaction */
    public $nau;

    /** @var EthTransaction */
    public $eth;
}
