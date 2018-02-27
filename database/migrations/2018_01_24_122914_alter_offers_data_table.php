<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOffersDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers_data', function (Blueprint $table) {
            $table->float('discount_percent')->unsigned()->nullable()->change();
            $table->float('discount_start_price')->unsigned()->nullable()->change();
            $table->string('currency', 3)->nullable();
            $table->dropColumn('gift_bonus_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers_data', function (Blueprint $table) {
            $table->smallInteger('discount_percent')->unsigned()->nullable()->change();
            $table->integer('discount_start_price')->unsigned()->nullable()->change();
            $table->string('gift_bonus_type', '5')->nullable();
            $table->dropColumn('currency');
        });
    }
}
