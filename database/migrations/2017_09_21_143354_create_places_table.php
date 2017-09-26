<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->string('name');
            $table->string('description')->nullable();
            $table->text('about')->nullable();
            $table->string('address')->nullable();
            $table->float('latitude')->index();
            $table->float('longitude')->index();
            $table->integer('radius')->index();
            $table->integer('stars')->index()->default(0);
            $table->boolean('is_featured')->index()->default(0);
            $table->timestamps();
        });

        Schema::table('places', function(Blueprint $table)
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
        Schema::dropIfExists('places');
    }
}
