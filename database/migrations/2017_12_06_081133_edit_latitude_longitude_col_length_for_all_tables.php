<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditLatitudeLongitudeColLengthForAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->float('latitude', 8, 5)->nullable()->change();
            $table->float('longitude', 8, 5)->nullable()->change();
        });

        Schema::table('places', function (Blueprint $table) {
            $table->float('latitude', 8, 5)->change();
            $table->float('longitude', 8, 5)->change();
        });
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
