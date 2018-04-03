<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColTimezoneToPlacesTable extends Migration
{
    public function up()
    {
        Schema::table('places', function (Blueprint $table) {
            $table->string('timezone')->nullable();
        });
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
