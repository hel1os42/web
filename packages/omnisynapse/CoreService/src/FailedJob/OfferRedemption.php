<?php

namespace OmniSynapse\CoreService\FailedJob;

use App\Models\NauModels\Redemption;
use OmniSynapse\CoreService\FailedJob;

/**
 * Class OfferRedemption
 * @package OmniSynapse\CoreService\FailedJob
 */
class OfferRedemption extends FailedJob
{
    /** @var Redemption */
    private $redemption;

    /**
     * @param \Exception $exception
     * @param Redemption|null $redemption
     */
    public function __construct(\Exception $exception, Redemption $redemption = null)
    {
        parent::__construct($exception);
        $this->redemption = $redemption;
    }

    /**
     * @return Redemption|null
     */
    public function getRedemption()
    {
        return $this->redemption;
    }
}
