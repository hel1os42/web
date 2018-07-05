<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class UserRepository
 * NS: App\Repositories
 *
 * @method User find($id, $columns = ['*'])
 * @method User setApproved(bool $approve)
 * @method User update(array $attributes, $id)
 * @method User create(array $attributes)
 * @method Collection|User[] findByField($field, $value, $columns = ['*'])
 */
interface UserRepository extends RepositoryInterface
{
    public function model(): string;

    public function findByPhone(string $phone): ?User;
    public function findByInvite(string $inviteCode): ?User;

    /**
     * @param User $user
     *
     * @return Builder
     */
    public function getChildrenByUser(User $user): Builder;

    /**
     * @param mixed $childrenIds
     * @return UserRepository
     */
    public function getChildrenByUsers($childrenIds): UserRepository;
}
