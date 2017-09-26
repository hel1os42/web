<?php

namespace App\Exceptions\Offer\Redemption;

use App\Exceptions\Offer\RedemptionException;
use App\Models\NauModels\Offer;
use Illuminate\Http\Response;

/**
 * Class NotEnoughFunds
 * NS: App\Exceptions\Offer\Redemption
 */
class NotEnoughFunds extends RedemptionException
{
    /**
     * NotEnoughFunds constructor.
     *
     * @param Offer  $offer
     * @param string $activationCode
     */
    public function __construct(Offer $offer, string $activationCode)
    {
        $message = sprintf('Not enough NAU on your account. You have: %d; you need: %d',
            $offer->account->balance, $offer->reward);

        parent::__construct($offer, $activationCode, $message, Response::HTTP_FORBIDDEN);
    }
}
