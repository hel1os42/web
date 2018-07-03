<?php

namespace App\Transformers;

use App\Models\User\FavoritePlaces;
use App\Repositories\OfferRepository;
use Carbon\Carbon;
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

            usort($resultModel['offers'], [$this, 'sortOffers']);
        }

        return $resultModel;
    }

    /**
     * @param array $offerDataA
     * @param array $offerDataB
     *
     * @return int
     */
    function sortOffers(array $offerDataA, array $offerDataB): int
    {
        $isFeaturedA = array_get($offerDataA, 'is_featured');
        $isFeaturedB = array_get($offerDataB, 'is_featured');

        if ($isFeaturedA === $isFeaturedB) {
            $updatedAtA = Carbon::parse(array_get($offerDataA, 'updated_at'));
            $updatedAtB = Carbon::parse(array_get($offerDataB, 'updated_at'));

            return $this->compareDates($updatedAtA, $updatedAtB);
        }

        return true === $isFeaturedA
            ? -1
            : 1;
    }

    /**
     * @param Carbon $dateA
     * @param Carbon $dateB
     *
     * @return int
     */
    private function compareDates(Carbon $dateA, Carbon $dateB): int
    {
        if ($dateA->equalTo($dateB)) {
            return 0;
        }

        return $dateA->greaterThan($dateB)
            ? -1
            : 1;
    }
}
