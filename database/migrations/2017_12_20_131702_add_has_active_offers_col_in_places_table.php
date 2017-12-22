<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHasActiveOffersColInPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('places', function (Blueprint $table) {
            $table->boolean('has_active_offers')->index()->default(0);
        });

        $places = DB::table('places')->get();

        foreach ($places as $place) {
            $accountId = DB::connection('pgsql_nau')->table('account')->where('owner_id', $place->user_id)->get(['id']);

            if (count($accountId)) {
                $activeOffers = DB::connection('pgsql_nau')->table('offer')
                                  ->where('acc_id', $accountId)
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
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('has_active_offers');
        });
    }
}
