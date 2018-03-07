<?php

namespace App\Transformers;

use App\Models\NauModels\Offer;
use App\Models\User\FavoriteOffers;
use Illuminate\Auth\AuthManager;
use League\Fractal\TransformerAbstract;

/**
 * Class OfferTransformer
 * @package namespace App\Transformers;
 */
class OfferTransformer extends TransformerAbstract
{
    private $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * @param Offer $model
     *
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function transform(Offer $model)
    {
        $model->setIsFavoriteAttribute(FavoriteOffers::checkByUserAndOffer($this->authManager->guard()->user(), $model))
              ->append('is_favorite');

        return $model->toArray();
    }
}
