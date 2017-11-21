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
    private $offer_id;

    public function __construct($offer_id)
    {
        $this->offer_id = $offer_id;
    }

    /**
     * @return string UUID
     */
    public function getOfferId()
    {
        return $this->offer_id;
    }
}
