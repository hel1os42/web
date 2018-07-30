<?php

namespace OmniSynapse\WebHookService\Transformers;

use App\Models\ActivationCode;
use App\Models\NauModels\Offer;
use League\Fractal\TransformerAbstract;

/**
 * Class ActivationCodeTransformer
 * @package OmniSynapse\WebHookService\Transformers
 */
class ActivationCodeTransformer extends TransformerAbstract
{

    /**
     * Transform the ActivationCode entity
     * @param ActivationCode $code
     *
     * @return array
     */
    public function transform(ActivationCode $code)
    {
        $offer = $code->offer;

        if (false === $offer instanceof Offer) {
            return [
                'id'       => $code->getKey(),
                'offer_id' => 0,
                'user_id'  => 0,
            ];
        }

        return [
            'id'       => $code->getKey(),
            'offer_id' => $offer->getKey(),
            'user_id'  => $offer->account->owner->getKey(),
        ];
    }
}
