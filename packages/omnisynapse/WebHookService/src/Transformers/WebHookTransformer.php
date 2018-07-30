<?php

namespace OmniSynapse\WebHookService\Transformers;

use App\Models\NauModels\Offer;
use League\Fractal\TransformerAbstract;
use OmniSynapse\WebHookService\Models\WebHook;

/**
 * Class OfferTransformer
 * @package namespace App\Transformers;
 */
class WebHookTransformer extends TransformerAbstract
{

    /**
     * Transform the Offer entity
     * @param Offer $webHook
     *
     * @return array
     */
    public function transform(WebHook $webHook)
    {
        $data = $webHook->toArray();

        if (array_has($data, 'event_names')) {
            $data['events'] = $data['event_names'];

            array_forget($data, 'event_names');
        }

        return $data;
    }
}
