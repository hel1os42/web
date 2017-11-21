<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 05.10.17
 * Time: 15:31
 */

namespace OmniSynapse\CoreService\Response;

/**
 * Class OfferDeleted
 * @package OmniSynapse\CoreService\Response
 */
class OfferDeleted
{
    private $offerId;

    public function __construct($offerId)
    {
        $this->offerId = $offerId;
    }

    /**
     * @return string UUID
     */
    public function getOfferId()
    {
        return $this->offerId;
    }
}
