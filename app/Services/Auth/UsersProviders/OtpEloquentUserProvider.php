<?php

namespace App\Services\Auth\UsersProviders;

use App\Services\Auth\Contracts\PhoneAuthenticable as UserContract;
use App\Services\Auth\Otp\OtpAuth;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;

class OtpEloquentUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     *
     * @return UserContract|null
     *
     * @throws \InvalidArgumentException
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return null;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (!Str::contains($key, ['password', 'code'])) {
                $query->where($key, $value);
            }
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $query->first();
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param Authenticatable|UserContract $user
     * @param array                        $credentials
     *
     * @return bool
     * @throws \Exception
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (false === $user instanceof UserContract) {
            return parent::validateCredentials($user, $credentials);
        }

        /** @var UserContract $user */
        return $this->checkCode($credentials['code'], $user->getPhone());
    }

    /**
     * @param string $code
     * @param string $phone
     *
     * @return bool
     */
    private function checkCode(string $code, string $phone): bool
    {
        /** @var OtpAuth $otpAuth */
        $otpAuth = app(OtpAuth::class);

        return $otpAuth->validateCode($phone, $code);
    }
}
