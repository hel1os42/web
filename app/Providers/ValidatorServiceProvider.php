<?php

namespace App\Providers;

use App\Repositories\AccountRepository;
use App\Repositories\ActivationCodeRepository;
use App\Services\Auth\Otp\OtpAuth;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Validator;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param ValidatorFactory         $validator
     * @param OtpAuth                  $otpAuth
     * @param AccountRepository        $accountRepository
     * @param ActivationCodeRepository $activationCodeRepository
     * @param AuthManager              $authManager
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function boot(
        ValidatorFactory $validator,
        OtpAuth $otpAuth,
        AccountRepository $accountRepository,
        ActivationCodeRepository $activationCodeRepository,
        AuthManager $authManager
    ) {
        $validator->extend('otp', $this->validateOtp($otpAuth));
        $validator->extend('ownAddress', $this->validateOwnAddress($accountRepository, $authManager->guard()));
        $validator->extend('enoughFor', $this->validateEnoughMoneyOnAccountFor($accountRepository));
        $validator->extend('can_redeem',
            $this->validateCanRedeemActivationCode($activationCodeRepository, $authManager->guard()));
    }

    /**
     * @param OtpAuth $otpAuth
     *
     * @return \Closure
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    private function validateOtp(OtpAuth $otpAuth): \Closure
    {
        /**
         * @param           $attribute
         * @param           $value
         * @param           $parameters
         * @param Validator $validator
         *
         * @return bool
         */
        return function (
            $attribute,
            $value,
            $parameters,
            Validator $validator
        ) use ($otpAuth): bool {
            $phone = $validator->getData()['phone'] ?? null;
            if (null === $phone) {
                return false;
            }

            /** @var OtpAuth $otpAuth */
            $otpAuth = app(OtpAuth::class);

            return $otpAuth->validateCode($phone, $value);
        };
    }

    /**
     * @param AccountRepository $accountRepository
     * @param Guard             $auth
     *
     * @return \Closure
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    private function validateOwnAddress(AccountRepository $accountRepository, Guard $auth): \Closure
    {
        /**
         * @param           $attribute
         * @param           $value
         *
         * @return bool
         */
        return function (
            /** @noinspection PhpUnusedParameterInspection */
            $attribute,
            $value
        ) use ($accountRepository, $auth): bool {
            return $auth->user()->isAdmin()
                ? $accountRepository->existsByAddress($value ?? null)
                : $accountRepository->existsByAddressAndOwner($value ?? null, $auth->user());
        };
    }

    /**
     * @param AccountRepository $accountRepository
     *
     * @return \Closure
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    private function validateEnoughMoneyOnAccountFor(AccountRepository $accountRepository): \Closure
    {
        /**
         * @param           $attribute
         * @param           $value
         * @param           $parameters
         * @param Validator $validator
         *
         * @return bool
         */
        return function (
            /** @noinspection PhpUnusedParameterInspection */
            $attribute,
            $value,
            $parameters,
            Validator $validator
        ) use ($accountRepository): bool {
            if (!isset($parameters[0], $validator->getData()[$parameters[0]])) {
                return false;
            }

            $amount = $validator->getData()[$parameters[0]];

            if (!is_numeric($amount)) {
                return false;
            }

            return $accountRepository->existsByAddressAndBalanceGreaterThan($value ?? null, $amount);
        };
    }

    /**
     * @param ActivationCodeRepository $activationCodeRepository
     * @param Guard                    $guard
     *
     * @return \Closure
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    private function validateCanRedeemActivationCode(ActivationCodeRepository $activationCodeRepository, Guard $guard)
    {
        /**
         * @param           $attribute
         * @param           $value
         * @param           $parameters
         * @param Validator $validator
         *
         * @return bool
         */
        return function (
            /** @noinspection PhpUnusedParameterInspection */
            $attribute,
            $value,
            $parameters,
            Validator $validator
        ) use ($activationCodeRepository, $guard): bool {
            if (null === $value) {
                return false;
            }

            $activationCode = $activationCodeRepository->findByCodeAndNotRedeemed($value);

            return null !== $activationCode
                   && null !== $activationCode->offer
                   && $activationCode->offer->isOwner($guard->user());
        };
    }
}
