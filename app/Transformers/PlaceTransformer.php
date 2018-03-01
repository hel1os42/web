<?php

namespace App\Transformers;

use App\Models\User\FavoritePlaces;
use Illuminate\Auth\AuthManager;
use League\Fractal\TransformerAbstract;
use App\Models\Place;

/**
 * Class PlaceTransformer
 * @package namespace App\Transformers;
 */
class PlaceTransformer extends TransformerAbstract
{
    private $authManager;

    public function __construct(AuthManager $authManager) {
        $this->authManager = $authManager;
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
        $model->setIsFavoriteAttribute(FavoritePlaces::checkByUserAndPlace($this->authManager->guard()->user(), $model));
        $model->append('is_favorite');
        return $model->toArray();
    }
}
