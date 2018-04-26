<?php

namespace App\Services;

use App\Exceptions\Offer\Redemption\BadActivationCodeException;
use App\Exceptions\Offer\Redemption\CannotRedeemException;
use App\Models\ActivationCode;
use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use App\Models\Timeframe;
use App\Repositories\ActivationCodeRepository;
use App\Repositories\OfferRepository;
use App\Repositories\TimeframeRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Access\Gate;
use OmniSynapse\CoreService\Exception\RequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class NauOffersService
 * NS: App\Services
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class NauOffersService implements OffersService
{
    private $activationCodeRepository;
    private $offerRepository;
    /**
     * @var Gate
     */
    private $gate;

    /**
     * @var WeekDaysService
     */
    private $weekDaysService;

    public function __construct(
        ActivationCodeRepository $activationCodeRepository,
        OfferRepository $offerRepository,
        Gate $gate,
        WeekDaysService $weekDaysService
    ) {
        $this->activationCodeRepository = $activationCodeRepository;
        $this->offerRepository          = $offerRepository;
        $this->gate                     = $gate;
        $this->weekDaysService          = $weekDaysService;
    }

    /**
     * @param Offer  $offer
     * @param string $code
     *
     * @return Redemption
     * @throws BadActivationCodeException
     * @throws CannotRedeemException
     */
    public function redeemByOfferAndCode(Offer $offer, string $code): Redemption
    {
        $activationCode = $this->activationCodeRepository
            ->findByCodeAndOfferAndNotRedeemed($code, $offer);

        if (null === $activationCode) {
            throw new BadActivationCodeException($offer, $code);
        }

        return $this->redeem($activationCode);
    }

    /**
     * @param string $code
     *
     * @return ActivationCode
     * @throws BadActivationCodeException
     */
    public function getActivationCodeByCode(string $code): ActivationCode
    {
        $activationCode = $this->activationCodeRepository
            ->findByCodeAndNotRedeemed($code);

        if (null === $activationCode) {
            throw new BadActivationCodeException(null, $code);
        }

        return $activationCode;
    }

    /**
     * @param ActivationCode $activationCode
     *
     * @return Redemption
     * @throws BadActivationCodeException
     * @throws CannotRedeemException
     */
    public function redeemByActivationCode(ActivationCode $activationCode)
    {
        $offer = $activationCode->offer;
        if (null === $offer) {
            throw new BadActivationCodeException($offer, $activationCode->code);
        }

        return $this->redeem($activationCode);
    }

    /**
     * @param ActivationCode $activationCode
     *
     * @return Redemption
     * @throws CannotRedeemException
     */
    private function redeem(?ActivationCode $activationCode): Redemption
    {
        try {
            /** @var Redemption $redemption */
            $redemption = $activationCode->offer->redemptions()->create([
                'user_id' => $activationCode->getUserId()
            ]);
        } catch (RequestException $exception) {
            throw new HttpException($exception->getCode(), $exception->getMessage(), $exception);
        } catch (\Throwable $throwable) {
            throw new HttpException(503, $throwable);
        }

        if (null === $redemption->id) {
            throw new CannotRedeemException($activationCode->offer, $activationCode->getCode());
        }

        $activationCode->redemption()->associate($redemption)->update();

        return $redemption;
    }

    /**
     * @param Offer $offer
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isActiveNowByWorkTime(Offer $offer): bool
    {
        $timezone               = $offer->account->owner->place->timezone;
        $timeframeTimezoneInSec = $offer->offerData->timeframes_offset;
        $timeframeTimezone      = new \DateTimeZone(sprintf("%+03d%02d", $timeframeTimezoneInSec / 3600,
            ($timeframeTimezoneInSec % 3600) / 60));

        /**
         * @var TimeframeRepository $timeframeRepository
         */
        $timeframeRepository = app(TimeframeRepository::class);
        $currentDate         = Carbon::now($timezone);
        $currentTime         = Carbon::createFromFormat('H:i:s', $currentDate->toTimeString(), $timezone);
        $timeframe           = $timeframeRepository->findByOfferAndDays($offer,
            $this->weekDaysService->weekDaysToDays([$currentDate->format('N')], true));
        if ($timeframe instanceof Timeframe
            && $currentTime->between(
                $this->getTimeWithTimezoneConvertion($timeframe->from, $timeframeTimezone),
                $this->getTimeWithTimezoneConvertion($timeframe->to, $timeframeTimezone)
            )
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param string        $timeString
     * @param \DateTimeZone $timezone
     *
     * @return Carbon
     * @throws \InvalidArgumentException
     */
    private function getTimeWithTimezoneConvertion(string $timeString, \DateTimeZone $timezone): Carbon
    {
        $dateTime = Carbon::createFromFormat('H:i:s', $timeString,
            new \DateTimeZone('UTC'))->setTimezone($timezone);
        return Carbon::createFromFormat('H:i:s', $dateTime->toTimeString(), $timezone);
    }
}
