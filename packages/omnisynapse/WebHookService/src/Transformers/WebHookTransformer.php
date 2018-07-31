<?php

namespace OmniSynapse\WebHookService\Transformers;

use League\Fractal\TransformerAbstract;
use OmniSynapse\WebHookService\Models\WebHook;

/**
 * Class WebHookTransformer
 * @package namespace App\Transformers;
 */
class WebHookTransformer extends TransformerAbstract
{

    /**
     * Transform the Offer entity
     * @param WebHook $webHook
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
