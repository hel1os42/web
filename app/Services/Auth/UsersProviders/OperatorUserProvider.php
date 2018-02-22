<?php

namespace App\Services\Auth\UsersProviders;

use App\Http\Exceptions\NotImplementedException;
use App\Models\Operator;
use App\Repositories\OperatorRepository;
use App\Repositories\PlaceRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;

class OperatorUserProvider implements UserProvider
{
    protected $placeRepository;
    protected $operatorRepository;

    public function __construct(PlaceRepository $placeRepository, OperatorRepository $operatorRepository)
    {
        $this->placeRepository    = $placeRepository;
        $this->operatorRepository = $operatorRepository;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return $this->operatorRepository->find($identifier);
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
        if (empty($credentials)
            || !isset($credentials['alias'])
            || !isset($credentials['login'])) {
            return null;
        }

        $place = $this->placeRepository->findByAlias($credentials['alias']);

        return $this->operatorRepository
            ->findByPlaceAndLogin($place, $credentials['login']);
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
        return $user instanceof \App\Models\Operator
            && $user->place->alias === $credentials['alias']
            && $user->login === $credentials['login']
            && Hash::check($credentials['password'], $user->password);
    }
}