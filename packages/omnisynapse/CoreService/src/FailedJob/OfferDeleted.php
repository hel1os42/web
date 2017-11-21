<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 05.10.17
 * Time: 14:55
 */

namespace OmniSynapse\CoreService\FailedJob;

use OmniSynapse\CoreService\FailedJob;

/**
 * Class OfferDeleted
 * @package OmniSynapse\CoreService\FailedJob
 */
class OfferDeleted extends FailedJob
{
    /** @var string UUID */
    private $offerId;

    /**
     * @param \Exception  $exception
     * @param string|null $offerId
     */
    public function __construct(\Exception $exception, ?string $offerId = null)
    {
        parent::__construct($exception);
        $this->offerId = $offerId;
    }

    /**
     * @return string|null
     */
    public function getOffer(): ?string
    {
        return $this->offerId;
    }
}
