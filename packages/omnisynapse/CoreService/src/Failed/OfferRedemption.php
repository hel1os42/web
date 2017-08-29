<?php

namespace OmniSynapse\CoreService\Failed;

use App\Models\NauModels\Redemption;

/**
 * Class OfferRedemption
 * @package OmniSynapse\CoreService\Failed;
 */
class OfferRedemption extends Failed
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
