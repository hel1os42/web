<?php

namespace OmniSynapse\WebHookService\Presenters;

use OmniSynapse\WebHookService\Transformers\WebHookTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class WebHookPresenter
 * @package Omnisynapse\WebHookService\Presenters
 */
class WebHookPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new WebHookTransformer();
    }
}
