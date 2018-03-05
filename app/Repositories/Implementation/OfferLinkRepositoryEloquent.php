<?php

namespace App\Repositories\Implementation;

use App\Models\NauModels\Account;
use App\Models\OfferLink;
use App\Models\User;
use App\Repositories\OfferLinkRepository;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class OfferRepositoryEloquent
 * @package namespace App\Repositories;
 *
 * @property OfferLink $model
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OfferLinkRepositoryEloquent extends BaseRepository implements OfferLinkRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return OfferLink::class;
    }

    /**
     * @param User $user
     *
     * @return OfferLinkRepository
     */
    public function scopeUser(User $user): OfferLinkRepository
    {
        return $this->scopeQuery(
            function ($builder) use ($user) {
                return $builder->where('user_id', $user->getId());
            }
        );
    }
}
