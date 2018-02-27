<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersFavoriteTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_favorite_places', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('place_id');
            $table->primary(['user_id', 'place_id'])->index();
        });

        Schema::table('users_favorite_places', function(Blueprint $table)
        {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('place_id')->references('id')->on('places');
        });

        Schema::create('users_favorite_offers', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('offer_id');
            $table->primary(['user_id', 'offer_id'])->index();
        });

        Schema::table('users_favorite_offers', function(Blueprint $table)
        {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_favorite_places');
        Schema::dropIfExists('users_favorite_offers');
    }
}
