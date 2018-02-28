<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 28.02.2018
 * Time: 13:48
 */

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FavoriteOffers
 * @package App\Models\User
 * @property string user_id
 * @property string offer_id
 * @method Builder byUser(User $user)
 */
class FavoriteOffers extends Model
{
    /**
     * FavoriteOffers constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');

        $this->table = 'users_favorite_offers';

        $this->fillable = [
            'user_id',
            'offer_id',
        ];

        parent::__construct($attributes);
    }


    /**
     * @param Builder $builder
     * @param User    $user
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeByUser(Builder $builder, User $user): Builder
    {
        return $builder->where('user_id', $user->getId());
    }
}
