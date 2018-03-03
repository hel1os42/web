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

    public function findByCodeAndUser($code, User $user): ?ActivationCode;

    public function findByCodeAndOfferAndNotRedeemed(string $code, Offer $offer): ?ActivationCode;

    public function findByCodeAndNotRedeemed(string $code): ?ActivationCode;

    public function findByCode(string $code): ?ActivationCode;
}
