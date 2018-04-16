<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeframeOffsetColToOfferDataTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('offers_data', 'timeframes_offset')) {
            Schema::table('offers_data', function (Blueprint $table) {
                $table->integer('timeframe_offset')->default(0);
            });
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
                $table->dropColumn('timeframe_offset');
            });
        }
    }
}
