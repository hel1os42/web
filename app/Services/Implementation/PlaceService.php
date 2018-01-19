<?php

namespace App\Services\Implementation;

use App\Models\NauModels\Offer;
use App\Models\Place;
use App\Repositories\SpecialityRepository;
use App\Repositories\TagRepository;
use App\Services\PlaceService as PlaceServiceImpl;

/**
 * Class PlaceService
 */
class PlaceService implements PlaceServiceImpl
{
    /**
     * @var SpecialityRepository
     */
    protected $specialityRepository;

    public function __construct(SpecialityRepository $specialityRepository ) {
        $this->specialityRepository = $specialityRepository;
    }

    /**
     * @param Place $place
     * @param bool  $setUserApprovedFlag
     *
     * @return mixed|void
     */
    public function disapprove(Place $place, bool $setUserApprovedFlag = false)
    {
        if ($place->hasActiveOffers()) {
            $offers = new Offer();
            $offers = $offers->byOwner($place->user);
            /**
             * @var Offer $offer
             */
            foreach ($offers as $offer) {
                $offer->setStatus(Offer::STATUS_DEACTIVE)->save();
            }
        }

        if ($setUserApprovedFlag) {
            $place->user->setApproved(false)->save();
        }
    }

    /**
     * @param array $specialities
     *
     * @return array
     */
    public function parseSpecialities(?array $specialities): array
    {
        if (0 == count($specialities) || null === $specialities) {
            return [];
        }

        $specsIds             = [];
        foreach ($specialities as $retailTypeSpecs) {
            if (!array_key_exists('retail_type_id', $retailTypeSpecs)
                || !array_key_exists('specs', $retailTypeSpecs)
            ) {
                continue;
            }
            $retailTypeId = $retailTypeSpecs['retail_type_id'];
            $specs        = $retailTypeSpecs['specs'];

            $specsIds = array_merge($specsIds,
                $this->getValidSpecs($retailTypeId, $specs));
        }

        return $specsIds;
    }

    /**
     * @param array $slugs
     *
     * @return array
     */
    protected function getValidSpecs($retailTypeId, array $slugs): array
    {
        $foundSpecs  = $this->specialityRepository->findByRetailTypeAndSlugs($retailTypeId, $slugs);
        $resultSpecs = $proceededGroups = [];

        foreach ($foundSpecs as $spec) {
            if (null === $spec->group) {
                $resultSpecs[] = $spec->id;
                continue;
            }
            if (!array_key_exists($spec->group, $proceededGroups)) {
                $resultSpecs[] = $spec->id;
                $proceededGroups[$spec->group] = $spec->group;
                continue;
            }
        }

        return $resultSpecs;
    }

    /**
     * @param string $category
     * @param array  $tags
     *
     * @return array
     */
    public function parseTags(string $category, ?array $tags): array
    {
        if (0 == count($tags) || null === $tags) {
            return [];
        }
        $tagRepository = app(TagRepository::class);
        return $tagRepository->findIdsByCategoryAndSlugs($category, $tags);
    }
}
