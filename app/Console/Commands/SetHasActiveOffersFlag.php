<?php

namespace App\Console\Commands;

use App\Models\NauModels\Account;
use App\Models\NauModels\Offer;
use App\Models\Place;
use Illuminate\Console\Command;

class SetHasActiveOffersFlag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develop:set-active-offers-flag-in-place-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set active offers flag in place table';

    /**
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @throws \InvalidArgumentException
     */
    public function handle()
    {
        $places = (new Place())->get();

        foreach ($places as $place) {
            $accountId = (new Account())->where('owner_id', $place->user_id)->get(['id']);

            if (isset($accountId[0])) {
                $activeOffers = (new Offer())->where('acc_id', $accountId[0]->id)
                                             ->where('status', 'active')
                                             ->get();
                if (count($activeOffers) > 0) {
                    $this->info(sprintf('Place %s has active offers. Set has_active_offers = true', $place->id));
                    (new Place())->find($place->id)->setHasActiveOffers(true)->update();
                }
            }
        }

        $this->info('Done. All places checked.');
    }
}
