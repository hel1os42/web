<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->uuid('category_id');
            $table->string('slug', 64)->index();
            $table->string('name', 64);
        });
        Schema::table('tags', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories');
            $table->unique(['category_id', 'slug']);
        });
        Schema::create('places_tags', function (Blueprint $table) {
            $table->uuid('place_id');
            $table->unsignedSmallInteger('tag_id');
            $table->primary(['place_id', 'tag_id'])->index();
        });
        Schema::table('places_tags', function(Blueprint $table)
        {
            $table->foreign('place_id')->references('id')->on('places');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places_tags');
        Schema::dropIfExists('tags');
    }
}
