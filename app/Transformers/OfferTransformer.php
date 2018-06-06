<?php

namespace App\Transformers;

use App\Models\NauModels\Offer;
use App\Models\User\FavoriteOffers;
use App\Services\OfferRedemption\Access\Moderator;
use App\Services\WeekDaysService;
use Illuminate\Auth\AuthManager;
use League\Fractal\TransformerAbstract;

/**
 * Class OfferTransformer
 * @package namespace App\Transformers;
 */
class OfferTransformer extends TransformerAbstract
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
     * OfferTransformer constructor.
     *
     * @param AuthManager     $authManager
     * @param WeekDaysService $weekDaysService
     */
    public function __construct(AuthManager $authManager, WeekDaysService $weekDaysService)
    {
        $this->authManager     = $authManager;
        $this->weekDaysService = $weekDaysService;
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

        $data = $model->toArray();

        if ($model->relationLoaded('timeframes')) {
            $data['timeframes'] = array_values($this->weekDaysService->processOfferTimeFrames($model));
        }

        $accessModerator = app()->makeWith(Moderator::class, [
            'offer'    => $model,
            'customer' => auth()->user(),
        ]);

        $data['redemption_access_code'] = $accessModerator->getAccessCode();

        $this->handleOfferPlaceData($data);

        return $data;
    }

    /**
     * @param array $data
     */
    private function handleOfferPlaceData(array &$data)
    {
        $placeKey = 'account.owner.place';

        if (array_has($data, $placeKey)) {
            $data['place'] = array_get($data, $placeKey);

            array_forget($data, 'account');
        }
    }
}
