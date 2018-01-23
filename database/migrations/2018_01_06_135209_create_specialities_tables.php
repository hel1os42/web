<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialitiesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialities', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->uuid('retail_type_id');
            $table->string('slug', 64)->index();
            $table->string('name', 64);
            $table->tinyInteger('group')->nullable();
        });
        Schema::table('specialities', function (Blueprint $table) {
            $table->foreign('retail_type_id')->references('id')->on('categories');
            $table->unique(['retail_type_id', 'slug']);
        });
        Schema::create('places_specialities', function (Blueprint $table) {
            $table->uuid('place_id');
            $table->unsignedSmallInteger('speciality_id');
            $table->primary(['place_id', 'speciality_id'])->index();
        });
        Schema::table('places_specialities', function(Blueprint $table)
        {
            $table->foreign('place_id')->references('id')->on('places');
            $table->foreign('speciality_id')->references('id')->on('specialities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places_specialities');
        Schema::dropIfExists('specialities');
    }
}
