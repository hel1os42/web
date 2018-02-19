<?php

namespace App\Services\Auth\UsersProviders;

use App\Http\Exceptions\NotImplementedException;
use App\Repositories\PlaceRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Support\Str;

class OperatorUserProvider extends EloquentUserProvider
{
//    protected $placeRepository;
//    protected $model;
//    protected $hasher;
//
//    public function __construct(HasherContract $hasher, $model, PlaceRepository $placeRepository)
//    {
//        $this->placeRepository = $placeRepository;
//        $this->model = $model;
//        $this->hasher = $hasher;
//        parent::__construct($hasher, $model);
//    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        throw new NotImplementedException();
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed   $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        throw new NotImplementedException();
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        throw new NotImplementedException();
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return null;
        }

        $credentials['place_uuid'] = 'abb1468d-1794-4869-9440-5d9886c61738';//$this->placeRepository->findIdByAlias($credentials['alias'])->id;
        unset($credentials['alias']);

        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (!Str::contains($key, ['password', 'login', 'place_uuid'])) {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return true;
    }
}