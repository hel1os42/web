<?php

namespace App\Services\Auth;

use Illuminate\Auth\SessionGuard;

class SmsGuard extends SessionGuard
{
    /**
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = []): bool
    {
        if ($credentials['phone'] !== null) {
            $this->lastAttempted = $this->provider->retrieveByCredentials(['phone' => $credentials['phone']]);

            return $this->checkCode($credentials['code'], $credentials['phone']);
        }
        unset($credentials['phone']);
        unset($credentials['code']);

        return parent::validate($credentials);
    }

    /**
     * @param array $credentials
     * @param bool $remember
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false): bool
    {
        if ($credentials['phone'] !== null) {

            $phone = $credentials['phone'];

            $user = $this->provider->retrieveByCredentials(['phone' => $phone]);
            if ($user === null) {
                return false;
            }



            if ($this->checkCode($credentials['code'], $credentials['phone'])) {
                $this->lastAttempted = $user;
                $this->login($user, $remember);
                return true;
            }
            return false;
        }
        unset($credentials['phone']);
        unset($credentials['code']);

        return parent::attempt($credentials, $remember);

    }

    /**
     * @param string $newCode
     * @param string $phone
     * @return bool
     */
    private function checkCode(string $newCode, string $phone): bool
    {
        $code = cache()->get($phone);
        return $code !== null && $newCode === $code;
    }
}
