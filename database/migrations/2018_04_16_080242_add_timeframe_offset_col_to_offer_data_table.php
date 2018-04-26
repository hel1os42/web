<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeframeOffsetColToOfferDataTable extends Migration
{
    /**
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function up()
    {
        if (!Schema::hasColumn('offers_data', 'timeframes_offset')) {
            Schema::table('offers_data', function (Blueprint $table) {
                $table->integer('timeframes_offset')->default(0);
            });
        }

        $offersData = class_exists('\App\Models\OfferData') ? (new \App\Models\OfferData())->get() : [];
        /**@var \App\Models\OfferData $offer */
        foreach ($offersData as $offer) {
            try {
                $placeTimezone = new DateTimeZone($offer->owner->place->timezone ?? 'UTC');
                $offer->update([
                    'timeframes_offset' => (new DateTime($offer->offer->updated_at))->setTimezone($placeTimezone)->getOffset()
                ]);
                $offer->save();
            } catch (\Exception $exception) {
                logger('Can\'t get offer timezone');
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
        if (Schema::hasColumn('offers_data', 'timeframes_offset')) {
            Schema::table('offers_data', function (Blueprint $table) {
                $table->dropColumn('timeframes_offset');
            });
        }
    }
}
