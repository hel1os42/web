<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 05.10.17
 * Time: 14:55
 */

namespace OmniSynapse\CoreService\FailedJob;

use App\Models\NauModels\Offer;
use OmniSynapse\CoreService\FailedJob;

/**
 * Class OfferDeleted
 * @package OmniSynapse\CoreService\FailedJob
 */
class OfferDeleted extends FailedJob
{
    /** @var Offer */
    private $offer;

    /**
     * @param \Exception $exception
     * @param Offer|null $offer
     */
    public function __construct(\Exception $exception, Offer $offer = null)
    {
        parent::__construct($exception);
        $this->offer = $offer;
    }

    /**
     * @return Offer|null
     */
    public function getOffer()
    {
        return $this->offer;
    }
}
