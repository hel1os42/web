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
 *
 * @method static bool hasEmptyBody()
 */
class OfferDeleted extends BaseResponse
{
    private $offerId;

    /**
     * @static bool
     */
    protected static $hasEmptyBody = true;

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
