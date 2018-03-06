<?php

namespace App\Observers;

use App\Models\OfferLink;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OfferLinkObserver
{
    /**
     * @param OfferLink $offerLink
     *
     * @throws HttpException
     */
    public function saving(OfferLink $offerLink)
    {
        $offerLink->description = clean($offerLink->getDescription());
    }
}
