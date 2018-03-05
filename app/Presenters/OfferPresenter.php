<?php

namespace App\Presenters;

use App\Transformers\OfferTransformer;
use Illuminate\Auth\AuthManager;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class OfferPresenter
 *
 * @package namespace App\Presenters;
 */
class OfferPresenter extends FractalPresenter
{
    private $authManager;

    /**
     * OfferPresenter constructor.
     *
     * @param AuthManager $authManager
     *
     * @throws \Exception
     */
    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
        parent::__construct();
    }

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new OfferTransformer($this->authManager);
    }
}
