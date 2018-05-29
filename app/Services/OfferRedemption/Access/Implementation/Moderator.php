<?php

namespace App\Services\OfferRedemption\Access\Implementation;

use App\Models\NauModels\Offer;
use App\Models\User;
use App\Services\OfferRedemption\Access\Rules;
use App\Services\OfferRedemption\Access\Moderator as AccessModeratorContract;

class Moderator implements AccessModeratorContract
{

    /**
     * @var Rules\Rule[]
     */
    private $rules = [];

    /**
     * @var Offer
     */
    public $offer;

    /**
     * @var User
     */
    public $customer;

    /**
     * AccessModerator constructor.
     *
     * @param Offer $offer
     * @param User $customer
     */
    public function __construct(Offer $offer, User $customer)
    {
        $this->offer    = $offer;
        $this->customer = $customer;

        $this->initRules();
    }

    /**
     * @return int
     */
    public function getAccessCode(): int
    {
        $failedRules = array_filter($this->rules, function (Rules\Rule $rule) {
            return false === $rule->validate();
        });

        $errorCodes = array_map(function (Rules\Rule $rule) {
            return $rule->getErrorCode();
        }, $failedRules);

        return array_sum($errorCodes);
    }

    /**
     * @return array
     */
    public function getRestrictions(): array
    {
        return [
            Rules\Rule::LIMIT_MAX_OFFER_TOTAL_REDEMPTIONS  => $this->offer->getMaxCount(),
            Rules\Rule::LIMIT_MAX_OFFER_DAILY_REDEMPTIONS  => $this->offer->getMaxPerDay(),
            Rules\Rule::LIMIT_MAX_USER_TOTAL_REDEMPTIONS   => $this->offer->getMaxForUser(),
            Rules\Rule::LIMIT_MAX_USER_DAILY_REDEMPTIONS   => $this->offer->getMaxForUserPerDay(),
            Rules\Rule::LIMIT_MAX_USER_WEEKLY_REDEMPTIONS  => $this->offer->getMaxForUserPerWeek(),
            Rules\Rule::LIMIT_MAX_USER_MONTHLY_REDEMPTIONS => $this->offer->getMaxForUserPerMonth(),
            Rules\Rule::LIMIT_MIN_USER_LEVEL               => $this->offer->getUserLevelMin(),
        ];
    }

    /**
     * @return array
     */
    public function mapRestrictionsToRules(): array
    {
        return [
            Rules\Rule::LIMIT_MAX_OFFER_TOTAL_REDEMPTIONS  => Rules\MaxTotalOfferRedemptionsCount::class,
            Rules\Rule::LIMIT_MAX_OFFER_DAILY_REDEMPTIONS  => Rules\MaxDailyOfferRedemptionsCount::class,
            Rules\Rule::LIMIT_MAX_USER_TOTAL_REDEMPTIONS   => Rules\MaxTotalUserRedemptionsCount::class,
            Rules\Rule::LIMIT_MAX_USER_DAILY_REDEMPTIONS   => Rules\MaxDailyUserRedemptionsCount::class,
            Rules\Rule::LIMIT_MAX_USER_WEEKLY_REDEMPTIONS  => Rules\MaxWeeklyUserRedemptionsCount::class,
            Rules\Rule::LIMIT_MAX_USER_MONTHLY_REDEMPTIONS => Rules\MaxMonthlyUserRedemptionsCount::class,
            Rules\Rule::LIMIT_MIN_USER_LEVEL               => Rules\MinUserLevel::class,
        ];
    }

    /**
     * @param int $restrictionCode
     *
     * @return string
     */
    private function getAppropriateRule(int $restrictionCode): string
    {
        return array_get($this->mapRestrictionsToRules(), $restrictionCode, '');
    }

    /**
     * @return void
     */
    private function initRules(): void
    {
        $restrictions = array_filter($this->getRestrictions());

        foreach ($restrictions as $restrictionCode => $restrictionValue) {
            $rule = $this->getAppropriateRule($restrictionCode);
            array_push($this->rules, new $rule($this->offer, $this->customer, $restrictionValue));
        }
    }
}
