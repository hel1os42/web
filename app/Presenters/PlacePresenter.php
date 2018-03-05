<?php

namespace App\Presenters;

use App\Repositories\OfferRepository;
use App\Transformers\PlaceTransformer;
use Illuminate\Auth\AuthManager;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PlacePresenter
 *
 * @package namespace App\Presenters;
 */
class PlacePresenter extends FractalPresenter
{
    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * @var OfferRepository
     */
    private $offerRepository;

    /**
     * PlacePresenter constructor.
     *
     * @param AuthManager $authManager
     *
     * @throws \Exception
     */
    public function __construct(AuthManager $authManager, OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
        $this->authManager     = $authManager;
        parent::__construct();
    }

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PlaceTransformer($this->authManager, $this->offerRepository);
    }
}
