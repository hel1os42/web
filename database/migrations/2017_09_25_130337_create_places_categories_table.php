<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places_categories', function (Blueprint $table) {
            $table->primary(['place_id', 'category_id']);
            $table->timestamps();
        });

        Schema::table('places_categories', function(Blueprint $table)
        {
            $table->foreign('place_id')->references('id')->on('places');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places_categories');
    }
}
