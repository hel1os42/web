<?php

namespace App\Console\Commands;

use App\Models\NauModels\Offer;
use App\Models\Scopes\OfferDateActual;
use App\Models\Scopes\OfferStatusActive;
use App\Repositories\OfferRepository;
use App\Services\OfferReservation;
use Illuminate\Console\Command;

class FixZeroReservedOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develop:fix-zero-reserved-offers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix offers with reserved === 0';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param OfferRepository  $offerRepository
     * @param OfferReservation $offerReservation
     *
     * @throws \InvalidArgumentException
     */
    public function handle(OfferRepository $offerRepository, OfferReservation $offerReservation)
    {
        $offers = (new Offer)->withoutGlobalScopes([OfferDateActual::class, OfferStatusActive::class])
                             ->where('reserved', 0)
                             ->get();

        $this->info(sprintf('Found %s offers for update.', $offers->count()));

        if (0 == $offers->count()) {
            $this->info('Bye! >;-)');
            return;
        }
        $offers->each(function ($offer) use ($offerRepository, $offerReservation) {
            $originalAttributes = $offer->toArray();

            $reserved        = $offerReservation->getMinReserved($offer->getReward());
            $offer->reserved = $reserved;
            $updatedOffer    = $offerRepository->update($offer->toArray(), $offer->getId());

            logger()->info($this->signature, ['original' => $originalAttributes, 'updated' => $updatedOffer->toArray()]);
            $this->info(sprintf('Offer id = %s processed. Details in log.', $offer->getId()));
        });
        $this->info('Done. Details in log.');
    }
}
