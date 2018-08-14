<?php

namespace OmniSynapse\WebHookService\Transformers;

use App\Models\NauModels\Offer;
use League\Fractal\TransformerAbstract;

/**
 * Class OfferTransformer
 * @package namespace App\Transformers;
 */
class OfferTransformer extends TransformerAbstract
{

    /**
     * Transform the Offer entity
     * @param Offer $offer
     *
     * @return array
     */
    public function transform(Offer $offer)
    {
        return [
            'id'      => $offer->getKey(),
            'user_id' => $offer->account->owner->getKey(),
        ];
    }
}
