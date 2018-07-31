<?php

namespace Omnisynapse\WebHookService\Presenters;

use OmniSynapse\WebHookService\Transformers\ActivationCodeTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ActivationCodePresenter
 * @package Omnisynapse\WebHookService\Presenters
 */
class ActivationCodePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ActivationCodeTransformer();
    }
}
