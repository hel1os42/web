<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Jobs\ProcessSetPlaceTimeZone;

class AddColTimezoneToPlacesTable extends Migration
{
    /**
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    public function up()
    {
        Schema::table('places', function (Blueprint $table) {
            $table->string('timezone')->nullable();
        });

        $places = DB::table('places')->get();

        foreach ($places as $place) {
            ProcessSetPlaceTimeZone::dispatch(\App\Services\TimezoneDbService::class, $place)->delay(1);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('places', 'timezone')) {
            Schema::table('places', function (Blueprint $table) {
                $table->dropColumn('timezone');
            });
        }
    }
}
