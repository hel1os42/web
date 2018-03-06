<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 28.02.2018
 * Time: 13:48
 */

namespace App\Models\User;

use App\Models\Place;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FavoritePlaces
 * @package App\Models\User
 * @property string user_id
 * @property string place_id
 * @method Builder byUser(User $user)
 */
class FavoritePlaces extends Model
{
    /**
     * FavoritePlaces constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');

        $this->table = 'users_favorite_places';

        $this->fillable = [
            'user_id',
            'place_id',
        ];

        parent::__construct($attributes);
    }

    /**
     * @param User  $user
     * @param Place $place
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public static function checkByUserAndPlace(User $user, Place $place): bool
    {
        return self::query()->where([['user_id', $user->getId()], ['place_id', $place->getId()]])->get()->count() === 1;
    }
}
