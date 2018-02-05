<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers_data', function (Blueprint $table)
        {
            $table->uuid('id')->primary();
            $table->boolean('delivery')->default(false);
//            types: discount, gift, bonus, second_free
            $table->string('type', 12)->nullable();
            $table->string('gift_bonus_type', '5')->nullable();
            $table->string('gift_bonus_descr')->nullable();
            $table->smallInteger('discount_percent')->unsigned()->nullable();
            $table->integer('discount_start_price')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers_data');
    }
}
