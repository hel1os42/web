<?php

namespace App\Services\Auth\UsersProviders;

use App\Http\Exceptions\NotImplementedException;
use App\Models\Operator;
use App\Repositories\OperatorRepository;
use App\Repositories\PlaceRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\UserProvider;

class OperatorUserProvider implements UserProvider
{
    protected $placeRepository;

    public function __construct(PlaceRepository $placeRepository)
    {
        $this->placeRepository = $placeRepository;
    }

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
     * @param  mixed  $identifier
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

        $credentials['place_uuid'] = $this->placeRepository->findByAlias($credentials['alias'])->id;

        unset($credentials['alias']);

        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (!Str::contains($key, ['password', 'login', 'place_uuid'])) {
                $query->where($key, $value);
            }
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $query->first();
    }

    /**
     * Create a new instance of the model operator.
     *
     * @return Operator
     */
    public function createModel()
    {
        return new Operator;
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
        if ($user instanceof Operator) {
            return !(empty($credentials['alias'])
                && empty($credentials['password'])
                && empty($credentials['place_uuid']));
        }
    }
}