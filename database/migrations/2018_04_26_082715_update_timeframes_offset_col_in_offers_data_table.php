<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTimeframesOffsetColInOffersDataTable extends Migration
{
    /**
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function up()
    {
        $offersData = (new \App\Models\OfferData())->get();
        /**@var \App\Models\OfferData $offer */
        foreach ($offersData as $offer) {
            $placeTimezone = $offer->owner->place->timezone;
            if($placeTimezone === "Europe/Kiev") {
                $placeTimezone = new DateTimeZone($placeTimezone);
                $offer->update([
                    'timeframes_offset' => (new DateTime($offer->offer->created_at))->setTimezone($placeTimezone)->getOffset()
                ]);
                $offer->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
