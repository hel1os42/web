<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\User\FavoriteOffers;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FavoriteOfferRepository
 * NS: App\Repositories
 *
 * @method FavoriteOffers find($id, $columns = ['*'])
 * @method FavoriteOffers create(array $attributes)
 */
interface FavoriteOfferRepository extends RepositoryInterface
{
    public function model(): string;

    public function getByUser(User $user): Builder;

    public function findByUserIdAndOfferId(string $userId, string $offerId): FavoriteOffers;
}
