<?php

namespace App\Repositories;

use App\Models\User;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface OfferLinkRepository
 * @package namespace App\Repositories;
 *
 */
interface OfferLinkRepository extends RepositoryInterface
{
    /**
     * @param User $user
     *
     * @return OfferLinkRepository
     */
    public function scopeUser(User $user): OfferLinkRepository;
}