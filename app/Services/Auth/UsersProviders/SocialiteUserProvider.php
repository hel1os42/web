<?php

namespace App\Services\Auth\UsersProviders;

use App\Http\Exceptions\NotImplementedException;
use App\Models\Identity;
use App\Models\IdentityProvider;
use App\Models\User;
use App\Repositories\IdentityProviderRepository;
use App\Repositories\IdentityRepository;
use App\Services\UserService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Laravel\Socialite\SocialiteManager;

class SocialiteUserProvider implements UserProvider
{
    /**
     * @var IdentityRepository
     */
    protected $identityRepository;

    /**
     * @var IdentityProviderRepository
     */
    protected $identityProviderRepository;

    /**
     * @var SocialiteManager
     */
    protected $manager;

    public function __construct(SocialiteManager $manager, IdentityRepository $identityRepository, IdentityProviderRepository $identityProviderRepository)
    {
        $this->identityRepository         = $identityRepository;
        $this->identityProviderRepository = $identityProviderRepository;
        $this->manager                    = $manager;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function retrieveById($identifier)
    {
        throw new NotImplementedException();
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed $identifier
     * @param  string $token
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function retrieveByToken($identifier, $token)
    {
        throw new NotImplementedException();
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string $token
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        throw new NotImplementedException();
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param array $credentials
     *
     * @return Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        if (!array_has($credentials, [
            'identity_provider',
            'identity_access_token'
        ])) {
            return null;
        }

        $providerAlias = array_get($credentials, 'identity_provider');
        $accessToken   = array_get($credentials, 'identity_access_token');

        $identityProvider = $this->identityProviderRepository
            ->findByField('alias', $providerAlias)->first();

        $userService = app(UserService::class);

        $oauthProvider = $userService->getOauthProvider($identityProvider);
        $user          = $userService->getOauthUser($oauthProvider, $accessToken);

        $identity = $this->identityRepository->findWhere([
            'identity_provider_id' => $identityProvider->getKey(),
            'external_user_id'     => $user->getId(),
        ])->first();

        if ($identity instanceof Identity) {
            return $identity->user;
        }

        return null;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $providerAlias = array_get($credentials, 'identity_provider');

        if (null == $providerAlias) {
            return false;
        }

        $identityProvider = $this->identityProviderRepository
            ->findByField('alias', $providerAlias)
            ->first();

        if (false === $identityProvider instanceof IdentityProvider) {
            return false;
        }

        return $user instanceof User &&
            $user->identities()->where('identity_provider_id', $identityProvider->getKey())->exists();
    }
}
