<?php

namespace App\Services\Implementation;

use App\Models\Identity;
use App\Models\IdentityProvider;
use App\Models\User;
use App\Repositories\IdentityProviderRepository;
use App\Repositories\IdentityRepository;
use App\Repositories\UserRepository;
use App\Services\UserService as UserServiceInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\AbstractUser as UserIdentity;

class UserService implements UserServiceInterface
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var IdentityProviderRepository
     */
    protected $idpRepository;

    /**
     * @var IdentityRepository
     */
    protected $identityRepository;

    /**
     * @var User/null
     */
    protected $issuer = null;

    public function __construct(
        UserRepository $userRepository,
        IdentityProviderRepository $idpRepository,
        IdentityRepository $identityRepository)
    {
        $this->userRepository     = $userRepository;
        $this->idpRepository      = $idpRepository;
        $this->identityRepository = $identityRepository;
    }

    /**
     * @param User|null $issuer
     *
     * @return UserService
     */
    public function setIssuer(User $issuer = null): self
    {
        $this->issuer = $issuer;

        return $this;
    }

    /**
     *  Create new or find registered user
     *
     * @param array $attributes
     *
     * @return User
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function make(array $attributes): User
    {
        $identityProvider = $this->getIdentityProvider($attributes);
        $userIdentity     = $this->getUserIdentity($attributes, $identityProvider);

        $registeredUser = $this->findRegisteredUser($attributes, $identityProvider, $userIdentity);

        if ($registeredUser instanceof User) {
            return $this->update($registeredUser, $attributes, $userIdentity, $identityProvider);
        }

        return $this->register($attributes, $userIdentity, $identityProvider);
    }

    /**
     * @param array $attributes
     * @param UserIdentity|null $userIdentity
     * @param IdentityProvider|null $identityProvider
     *
     * @return User
     *
     * @throws AuthorizationException
     */
    public function register(array $attributes, UserIdentity $userIdentity = null, IdentityProvider $identityProvider = null): User
    {
        $this->validateUserUniqueness($attributes);

        $newUser = $this->userRepository->create($attributes);

        $newUser = $this->createRelationData($newUser, $attributes);

        if ($userIdentity instanceof UserIdentity && $identityProvider instanceof IdentityProvider) {
            $this->createOrUpdateUserIdentity($newUser, $userIdentity, $identityProvider);
        }

        return $newUser;
    }

    /**
     * @param User $user
     * @param array $attributes
     * @param UserIdentity|null $userIdentity
     * @param IdentityProvider|null $identityProvider
     *
     * @return User
     */
    private function update(User $user, array $attributes, UserIdentity $userIdentity = null, IdentityProvider $identityProvider = null): User
    {
        if ($userIdentity instanceof UserIdentity && $identityProvider instanceof IdentityProvider) {
            $this->createOrUpdateUserIdentity($user, $userIdentity, $identityProvider);
        }

        $userAttributes = array_only($attributes, $user->getFillable());

        return $this->userRepository->update($userAttributes, $user->getKey());
    }

    /**
     * @param array $attributes
     * @param IdentityProvider|null $identityProvider
     * @param UserIdentity|null $userIdentity
     *
     * @return User|null
     */
    private function findRegisteredUser(array $attributes, IdentityProvider $identityProvider = null, UserIdentity $userIdentity = null): ?User
    {
        $registeredUser = null;

        $phone = array_get($attributes, 'phone');

        if (null !== $phone) {
            $registeredUser = $this->userRepository->findByPhone($phone);
        }

        if (null === $registeredUser) {
            $registeredUser = $this->findRegisteredUserByUserIdentity($identityProvider, $userIdentity);
        }

        return $registeredUser;
    }

    /**
     * @param array $attributes
     *
     * @return IdentityProvider|null
     */
    private function getIdentityProvider(array $attributes): ?IdentityProvider
    {
        $identityProviderAlias = array_get($attributes, 'identity_provider', '');

        if (strlen($identityProviderAlias) > 0) {
            return $this->idpRepository->findByField('alias', $identityProviderAlias)->first();
        }

        return null;
    }

    /**
     * @param array $attributes
     * @param IdentityProvider|null $identityProvider
     *
     * @return UserIdentity|null
     */
    private function getUserIdentity(array $attributes, IdentityProvider $identityProvider = null): ?UserIdentity
    {
        $identityAccessToken = array_get($attributes, 'identity_access_token');

        if (null === $identityAccessToken || null === $identityProvider) {
            return null;
        }

        $oauthProvider = $this->getOauthProvider($identityProvider);

        return $this->getOauthUser($oauthProvider, $identityAccessToken);
    }

    /**
     * @param IdentityProvider $identityProvider
     *
     * @return mixed
     */
    public function getOauthProvider(IdentityProvider $identityProvider)
    {
        $alias = $identityProvider->getAlias();

        if ($alias === 'vk') {
            $alias = 'vkontakte';
        }

        return Socialite::driver($alias);
    }

    /**
     * @param $oauthProvider
     * @param string $accessToken
     *
     * @return \Laravel\Socialite\AbstractUser|null
     */
    public function getOauthUser($oauthProvider, string $accessToken): ?\Laravel\Socialite\AbstractUser
    {
        switch (get_parent_class($oauthProvider)) {
            case \Laravel\Socialite\Two\AbstractProvider::class:
            case \SocialiteProviders\Manager\OAuth2\AbstractProvider::class:
                return $oauthProvider->userFromToken($accessToken);
            case \Laravel\Socialite\One\AbstractProvider::class:
            case \SocialiteProviders\Manager\OAuth1\AbstractProvider::class:
                list($token, $secret) = explode(':', $accessToken, 2) + array_fill(0, 2, '');

                return $oauthProvider->userFromTokenAndSecret($token, $secret);
        }

        return null;
    }

    /**
     * @param array $attributes
     *
     * @return void
     */
    private function validateUserUniqueness(array $attributes)
    {
        $uniquenessRules = [
            'email' => 'nullable|unique:users,email',
            'phone' => 'nullable|unique:users,phone',
        ];

        Validator::validate($attributes, $uniquenessRules);
    }

    /**
     * @param User $user
     * @param array $newUserData
     *
     * @return User
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    private function createRelationData(User $user, array $newUserData): User
    {
        $with = [];

        if (array_has($newUserData, 'role_ids')) {
            $this->updateRoles($user, array_get($newUserData, 'role_ids'));
            array_push($with, 'roles');
        }

        if (array_has($newUserData, 'parent_ids')) {
            $this->updateParents($user, array_get($newUserData, 'parent_ids'));
            array_push($with, 'parents');
        }

        if (!empty($with)) {
            $user->save();

            return $user->fresh($with);
        }

        return $user;
    }

    /**
     * @param User $user
     * @param array $roleIds
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    private function updateRoles(User $user, array $roleIds)
    {
        if (null == $this->issuer || $this->issuer->cannot('user.update.roles', [$user, $roleIds])) {
            throw new AuthorizationException(trans('exception.authorization_exceptions'));
        }

        $user->roles()->sync($roleIds, true);
    }

    /**
     * @param User $user
     * @param array $parentIds
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    private function updateParents(User $user, array $parentIds)
    {
        if (null == $this->issuer || $this->issuer->cannot('user.update.parents', [$user, $parentIds])) {
            throw new AuthorizationException(trans('exception.authorization_exceptions'));
        }

        $user->parents()->attach($parentIds);
    }

    /**
     * @param IdentityProvider $identityProvider
     * @param UserIdentity $userIdentity
     *
     * @return User|null
     */
    private function findRegisteredUserByUserIdentity(IdentityProvider $identityProvider = null, UserIdentity $userIdentity = null): ?User
    {
        if (null === $userIdentity || null === $identityProvider) {
            return null;
        }

        $identity = $this->identityRepository->findWhere([
            'identity_provider_id' => $identityProvider->getKey(),
            'external_user_id'     => $userIdentity->getId(),
        ])->first();

        if ($identity instanceof Identity) {
            return $identity->user;
        }

        $email = $userIdentity->getEmail();

        return strlen($email) > 0
            ? $this->userRepository->findByField('email', $email)->first()
            : null;
    }

    /**
     * @param User $user
     * @param UserIdentity $userIdentity
     * @param IdentityProvider $identityProvider
     *
     * @return Identity
     *
     * @throws ValidationException
     */
    public function createOrUpdateUserIdentity(User $user, UserIdentity $userIdentity, IdentityProvider $identityProvider): Identity
    {
        $identityData = [
            'external_user_id'     => $userIdentity->getId(),
            'identity_provider_id' => $identityProvider->getKey(),
        ];

        $identity = $this->identityRepository->firstOrNew($identityData);

        if ($identity->exists && $identity->user->getKey() !== $user->getKey()) {
            $validator = Validator::make([], []); // Empty data and rules fields
            $validator->errors()->add('identity_access_token', 'Account already has been taken');
            throw new ValidationException($validator);
        }

        $identity->user()->associate($user)
            ->save();

        return $identity;
    }

}