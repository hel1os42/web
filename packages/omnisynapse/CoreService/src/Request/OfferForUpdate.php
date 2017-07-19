<?php

namespace OmniSynapse\CoreService\Request;

/**
 * Class OfferForUpdate.
 * @package OmniSynapse\CoreService\Request
 *
 * @property string id
 */
class OfferForUpdate extends Offer implements \JsonSerializable
{
    /** @var string */
    public $id;

    /**
     * @param string $id
     * @return OfferForUpdate
     */
    public function setId(string $id) : OfferForUpdate
    {
        $this->id = $id;
        return $this;
    }
}