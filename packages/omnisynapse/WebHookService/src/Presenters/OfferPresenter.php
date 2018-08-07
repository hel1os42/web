<?php

namespace Omnisynapse\WebHookService\Presenters;

use OmniSynapse\WebHookService\Transformers\OfferTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class OfferPresenter
 * @package Omnisynapse\WebHookService\Presenters
 */
class OfferPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new OfferTransformer();
    }
}
