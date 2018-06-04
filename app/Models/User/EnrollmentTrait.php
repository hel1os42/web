<?php

namespace App\Models\User;

use App\Models\Role;

/**
 * Trait RoleTrait
 *
 * @package App\Models\User
 */
trait EnrollmentTrait
{
    /**
     * @return void
     */
    public function enrollRedemptionPoints()
    {
        $this->increment('redemption_points');
    }

    /**
     * @return void
     */
    public function enrollReferralPoints()
    {
        $this->increment('referral_points');
    }

    /**
     * @param int $points
     *
     * @return void
     */
    public function withdrawRedemptionPoints(int $points)
    {
        $this->decrement('redemption_points', $points);
    }

    /**
     * @param int $points
     *
     * @return void
     */
    public function withdrawReferralPoints(int $points)
    {
        $this->decrement('referral_points', $points);
    }
}
