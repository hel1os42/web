<?php

use Illuminate\Database\Migrations\Migration;

class SetupValuesForHasActiveOffersColInPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $places = DB::table('places')->get();

        foreach ($places as $place) {
            $accountId = DB::connection('pgsql_nau')->table('account')->where('owner_id', $place->user_id)->get(['id']);

            if (isset($accountId[0])) {
                $activeOffers = DB::connection('pgsql_nau')->table('offer')
                                  ->where('acc_id', $accountId[0]->id)
                                  ->where('status', 'active')
                                  ->get();
                if (count($activeOffers) > 0) {
                    DB::table('places')
                      ->where('id', $place->id)
                      ->update(['has_active_offers' => true]);
                }
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
        //
    }
}
