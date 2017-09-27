<?php

namespace App\Repositories;

use App\Models\ActivationCode;
use App\Models\NauModels\Offer;
use App\Models\User;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ActivationCodeRepository
 * @package App\Repositories
 *
 * @method ActivationCode firstOrNew(array $attributes = [])
 */
interface ActivationCodeRepository extends RepositoryInterface
{
    public function model(): string;

    public function findByCodeAndOwner($code, User $user): ?ActivationCode;

    public function findByCodeAndOfferAndNotRedeemed($code, Offer $offer): ?ActivationCode;
}
