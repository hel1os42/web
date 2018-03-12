<?php

namespace App\Transformers;

use App\Models\User\FavoritePlaces;
use App\Repositories\OfferRepository;
use Illuminate\Auth\AuthManager;
use League\Fractal\TransformerAbstract;
use App\Models\Place;

/**
 * Class PlaceTransformer
 * @package namespace App\Transformers;
 */
class PlaceTransformer extends TransformerAbstract
{
    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * @var OfferRepository
     */
    private $offerRepository;

    public function __construct(AuthManager $authManager, OfferRepository $offerRepository)
    {
        $this->authManager     = $authManager;
        $this->offerRepository = $offerRepository;
        $this->offerRepository->setPresenter(\App\Presenters\OfferPresenter::class);
    }

    /**
     * Transform the \Place entity
     *
     * @param Place $model
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function transform(Place $model)
    {
        $model->setIsFavoriteAttribute(FavoritePlaces::checkByUserAndPlace($this->authManager->guard()->user(),
            $model))
              ->append('is_favorite');

        $resultModel = $model->toArray();
        if (isset($model->offers) && count($model->offers)) {
            $resultModel['offers'] = $this->offerRepository->parserResult($model->offers)['data'];
        }

        return $resultModel;
    }
}
