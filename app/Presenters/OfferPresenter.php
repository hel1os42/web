<?php

namespace App\Presenters;

use App\Services\WeekDaysService;
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
    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * @var WeekDaysService
     */
    private $weekDaysService;

    /**
     * OfferPresenter constructor.
     *
     * @param AuthManager $authManager
     * @param WeekDaysService $weekDaysService
     *
     * @throws \Exception
     */
    public function __construct(AuthManager $authManager, WeekDaysService $weekDaysService)
    {
        $this->authManager     = $authManager;
        $this->weekDaysService = $weekDaysService;

        parent::__construct();
    }

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new OfferTransformer($this->authManager, $this->weekDaysService);
    }
}
